<?php

namespace Engine;

use SoapClient;

class Engine
{

    private $db_src;
    private $db_dst;
    private $wsdl;
    private $sync_every;

    public function __construct($db_src, $db_dst, $wsdl)
    {
        $this->db_src = $db_src;
        $this->db_dst = $db_dst;
        $this->wsdl = $wsdl;

        $this->get_param();
    }

    public function validate_nip($str)
    {
        $str = preg_replace('/[^0-9]+/', '', $str);

        if (strlen($str) !== 10) {
            return false;
        }

        $arrSteps = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $intSum = 0;

        for ($i = 0; $i < 9; $i++) {
            $intSum += $arrSteps[$i] * $str[$i];
        }

        $int = $intSum % 11;
        $intControlNr = $int === 10 ? 0 : $int;

        if ($intControlNr == $str[9]) {
            return true;
        }

        return false;
    }

    public function get_param()
    {
        $query = "SELECT sync_every FROM settings";
        $this->db_dst->query($query);
        $array = $this->db_dst->single();
        $this->sync_every = $array['sync_every'];
    }

    private function parse_nip($NIP)
    {
        $txt = str_replace('-', '', $NIP);
        return $txt;
    }

    private function prepare()
    {
        $query = "SELECT TOP 20 kh_id, kh_Symbol, adr_Nazwa, adr_NIP FROM kh__Kontrahent INNER JOIN adr__Ewid ON kh_id = adr_IdObiektu and adr_TypAdresu=1";
        $this->db_src->query($query);
        $rs = $this->db_src->resultset();
        return $rs;
    }

    public function process()
    {
        $records = $this->prepare();

        $client = new SoapClient($this->wsdl);

        foreach ($records as $record) {

            $parsed_NIP = $this->parse_nip($record['adr_NIP']);

            if ($this->validate_nip($parsed_NIP)) {

                $response = $client->__soapCall("SprawdzNIP", array($parsed_NIP));
                $array = json_decode(json_encode($response), True);

                $query = "SELECT COUNT(*) as exist FROM kontrahent_status WHERE nip=:nip";
                $this->db_dst->query($query);
                $exist = $this->db_dst->single(array(':nip' => $parsed_NIP));
                $exist = $exist['exist'];

                if ($exist == 1) {

                    $adr_nazwa = iconv("Windows-1250", "UTF-8", $record['adr_Nazwa']);

                    $query = "SELECT COUNT(*) as process FROM kontrahent_status WHERE nip='" . $parsed_NIP . "' AND data_aktualizacji < NOW() - INTERVAL " . $this->sync_every . " MINUTE";
                    $this->db_dst->query($query);
                    $rs = $this->db_dst->single();

                    if($rs['process'] == 1){
                        $update = "UPDATE kontrahent_status SET nazwa=:nazwa, kod=:kod, komunikat=:komunikat, data_aktualizacji=NOW() WHERE nip=:nip";
                        $this->db_dst->query($update);
                        $this->db_dst->execute(array(':nazwa' => $adr_nazwa, ':kod' => $array['Kod'], ':komunikat' => $array['Komunikat'], ':nip' => $parsed_NIP));
                        if($this->db_dst->rowCount()){
                            echo "Wpis zosta³ zaktualizowany.\n";
                        }
                    }

                } else {

                    $adr_nazwa = iconv("Windows-1250", "UTF-8", $record['adr_Nazwa']);

                    $this->db_dst->query('INSERT INTO kontrahent_status (nazwa, nip, kod, komunikat) VALUES (:nazwa, :nip, :kod, :komunikat)');
                    $this->db_dst->execute(array(':nazwa' => $adr_nazwa, ':nip' => $parsed_NIP, ':kod' => $array['Kod'], ':komunikat' => $array['Komunikat']));
                    if($this->db_dst->rowCount()){
                        echo "Wpis zosta³ dodany.\n";
                    }
                }

            }

            unset($exist);

            // TODO: too much task in one function. other maybe ?!
        }

    }

}