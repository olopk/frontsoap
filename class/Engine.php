<?php

namespace Engine;

use SoapClient;

class Engine
{

    private $db_src;
    private $db_dst;
    private $wsdl;
    private $sync_every;
    private $soap_client;

    public function __construct($db_src, $db_dst, $wsdl)
    {
        $this->db_src = $db_src;
        $this->db_dst = $db_dst;
        $this->wsdl = $wsdl;

        $this->get_param();
        $this->soap_client = new SoapClient($this->wsdl);
    }

    public function run($nip)
    {
        $response = $this->soap_client->__soapCall("SprawdzNIP", array($nip));
        $array = json_decode(json_encode($response), True);
        return $array;
    }

    private function get_param()
    {
        $query = "SELECT sync_every FROM settings";
        $this->db_dst->query($query);
        $array = $this->db_dst->single();
        $this->sync_every = $array['sync_every'];
    }

    private function parse_nip($NIP)
    {
        $txt = str_replace('-', '', $NIP);
        $txt = preg_replace('/\s+/', '', $txt);
        $txt = substr($txt, 0, 10);
        return $txt;
    }

    private function prepare()
    {
        $query = "SELECT kh_id, kh_Symbol, adr_Nazwa, adr_NIP FROM kh__Kontrahent INNER JOIN adr__Ewid ON kh_id = adr_IdObiektu and adr_TypAdresu=1";
        $this->db_src->query($query);
        $rs = $this->db_src->resultset();
        return $rs;
    }

    public function process()
    {
        $records = $this->prepare();

        foreach ($records as $record) {

            $parsed_NIP = $this->parse_nip($record['adr_NIP']);

            if ($this->validate_nip($parsed_NIP)) {

                $query = "SELECT COUNT(*) as exist FROM kontrahent_status WHERE nip=:nip ORDER BY data_aktualizacji DESC";
                $this->db_dst->query($query);
                $exist = $this->db_dst->single(array(':nip' => $parsed_NIP));
                $exist = $exist['exist'];

                if ($exist > 0) {
                    $adr_nazwa = iconv("Windows-1250", "UTF-8", $record['adr_Nazwa']);

                    $query = "SELECT COUNT(*) as process FROM kontrahent_status WHERE nip='" . $parsed_NIP . "' AND data_aktualizacji < NOW() - INTERVAL " . $this->sync_every . " MINUTE";
                    $this->db_dst->query($query);
                    $rs = $this->db_dst->single();

                    if ($rs['process'] > 0) {
                        $array = $this->run($parsed_NIP);
                        $this->update(array(':nazwa' => $adr_nazwa, ':kod' => $array['Kod'], ':komunikat' => $array['Komunikat'], ':nip' => $parsed_NIP));
                        if ($this->db_dst->rowCount()) {
                            echo "Record updated.\n";
                        }
                    }

                } else {
                    $array = $this->run($parsed_NIP);
                    $adr_nazwa = iconv("Windows-1250", "UTF-8", $record['adr_Nazwa']);
                    $this->store(array(':nazwa' => $adr_nazwa, ':nip' => $parsed_NIP, ':kod' => $array['Kod'], ':komunikat' => $array['Komunikat']));
                    if ($this->db_dst->rowCount()) {
                        echo "Record inserted.\n";
                    }
                }

            }

            unset($exist);

        }

    }

    private function store($array)
    {
        $this->db_dst->query('INSERT INTO kontrahent_status (nazwa, nip, kod, komunikat) VALUES (:nazwa, :nip, :kod, :komunikat)');
        $this->db_dst->execute($array);
    }

    private function update($array)
    {
        $this->db_dst->query('UPDATE kontrahent_status SET nazwa=:nazwa, kod=:kod, komunikat=:komunikat, data_aktualizacji=NOW() WHERE nip=:nip');
        $this->db_dst->execute($array);
    }

    private function validate_nip($str)
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

}