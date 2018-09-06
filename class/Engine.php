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

        $query = "SELECT TOP 100 kh_id, kh_Symbol, adr_Nazwa, adr_NIP FROM kh__Kontrahent INNER JOIN adr__Ewid ON kh_id = adr_IdObiektu and adr_TypAdresu=1";
        $this->db_src->query($query);
        $rs = $this->db_src->resultset();
        return $rs;

    }

    public function process()
    {
        $records = $this->prepare();

        $client = new SoapClient($wsdl);

        foreach ($records as $record) {

            $response = $client->__soapCall("SprawdzNIP", $this->parseNIP($record['adr_NIP']));
            $array = json_decode(json_encode($response), True);

            // other function to check ? !
            $query = "SELECT * FROM kontrahent_status WHERE nip='" . $record[$col_nip] . "';";
            $check = $this->db_dst->query($query);


            if (!$check->rowCount() > 0)
            {
                $insert = "INSERT INTO kontrahent_status (nazwa, nip, kod, komunikat) VALUES ('" . $record[$col_contractor] . "', '" . $record[$col_nip] . "', '" . $array['Kod'] . "', '" . $array['Komunikat'] . "')";
                $this->db_dst->query($insert);
                $this->db_dst->execute();

            }
            else
            {

                $update = "UPDATE kontrahent_status SET nazwa ='" . $record[$col_contractor] . "', kod = '" . $array['Kod'] . "', komunikat = '" . $array['Komunikat'] . "' WHERE nip='" . $record[$col_nip] . "';";
                $this->db_dst->query($update);
                $this->db_dst->execute();

            }
        }


    }


}