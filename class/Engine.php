<?php

namespace Engine;
use SoapClient;

class Engine
{

    private $db_src;
    private $db_dst;
    private $wsdl;

    public function __construct($db_src, $db_dst, $wsdl)
    {
        $this->db_src = $db_src;
        $this->db_dst = $db_dst;
        $this->wsdl = $wsdl;
    }

    private function parseNIP($NIP)
    {
        $txt = str_replace('-', '', $NIP);
        return $txt;
    }

    private function prepare()
    {
        $query = "SELECT TOP 200 kh_id, kh_Symbol, adr_Nazwa, adr_NIP FROM kh__Kontrahent INNER JOIN adr__Ewid ON kh_id = adr_IdObiektu and adr_TypAdresu=1";
        $this->db_src->query($query);
        $rs = $this->db_src->resultset();
        return $rs;
    }

    public function process()
    {
        $records = $this->prepare();

        $client = new SoapClient($this->wsdl);

        foreach ($records as $record) {

            $parsed_NIP = $this->parseNIP($record['adr_NIP']);
            $response = $client->__soapCall("SprawdzNIP", array($parsed_NIP));
            $array = json_decode(json_encode($response), True);

            // TODO: too much task in one function. other maybe ?!
            $query = "SELECT * FROM kontrahent_status WHERE nip='" . $parsed_NIP . "';";
            $this->db_dst->query($query);
            $this->db_dst->execute();

            // TODO: Is it really chechking 'komunikat' change

            if (!$this->db_dst->rowCount() > 0) {

                $adr_nazwa = iconv("Windows-1250","UTF-8", $record['adr_Nazwa']);

                $this->db_dst->query('INSERT INTO kontrahent_status (nazwa, nip, kod, komunikat) VALUES (:nazwa, :nip, :kod, :komunikat)');
                $this->db_dst->execute(array(':nazwa' => $adr_nazwa, ':nip' => $parsed_NIP, ':kod' => $array['Kod'], ':komunikat' => $array['Komunikat']));

            }
            else
            {
                $adr_nazwa = iconv("Windows-1250","UTF-8", $record['adr_Nazwa']);

                $update = "UPDATE kontrahent_status SET nazwa=:nazwa, kod=:kod, komunikat=:komunikat WHERE nip=:nip";
                $this->db_dst->query($update);
                $this->db_dst->execute(array(':nazwa' => $adr_nazwa, ':kod' => $array['Kod'], ':komunikat' => $array['Komunikat'], ':nip' => $parsed_NIP));
            }
        }

    }

    //TODO: Check update time setting (example: every 15min)


}