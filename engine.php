<?php

//class Dbc{
//    private $type = '';
//    private $host = '';
//    private $dbname = '';
//    private $username = '';
//    private $password = '';
//    public $conn = '';
//    
//    function __construct($type, $host, $dbname, $username, $password){
//        try{
//        $conn = new PDO("$type:host=$host;dbname=$dbname", $username, $password);
//        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        echo "it works.";
//        }
//        catch(PDOException $e){
//            echo "Something goes wrong: " . $e->getMessage();
//        }
//    }
//    
//}

//$test = new Dbc('mysql','localhost','subisekt','root','');
////$records = $test->$conn->query('SELECT * FROM kontrahenci');  
////var_dump($records);
//
//$conn = new PDO("mysql:host=localhost;dbname=subisekt", 'root', '');



function load(){
    
        $records = '';
        $records_checked = '';

        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $wsdl = 'http://localhost/soap/wsdl.xml';
        //connection to the Subiect DB to retrieve the contractors data.

        try{
            $conn = new PDO("mysql:host=$servername;dbname=subdiekt", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
            $records = $conn->query('SELECT * FROM kontrahenci');     
        }
        catch(PDOException $e){
            echo "Something goes wrong: " . $e->getMessage();
        }
        //connection to the APP DB to Insert, or Update the data.
        try{
            $conn = new PDO("mysql:host=$servername;dbname=aplikacja", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //prepare a SoapClient to connect with the API and retrieve the data from it.
            $client = new SoapClient($wsdl);
            //loop through the all of contractors and check their status.
            foreach($records as $record){
                $response = $client->__soapCall("SprawdzNIP", array($record['nip']));            
                $array = json_decode(json_encode($response), True);
                // now we check if the contractor already exists in the db, if it is we only update his status.
                $c = "SELECT * FROM kontrahent_status WHERE nip='".$record['nip']."';";
                $check = $conn->query($c);

                if(!$check->rowCount() > 0){    
                    $insert = "INSERT INTO kontrahent_status (nazwa, nip, kod, komunikat) VALUES ('".$record['nazwa']."', '".$record['nip']."', '".$array['Kod']."', '".$array['Komunikat']."')";  
                    $conn->query($insert);
                }
                else{
                    $update = "UPDATE kontrahent_status SET nazwa ='".$record['nazwa']."', kod = '".$array['Kod']."', komunikat = '".$array['Komunikat']."' WHERE nip='".$record['nip']."';";
                    $conn->query($update);
                }
            }
            //dump all the results of the app db to list them in the html.
            $records_checked = $conn->query('SELECT * FROM kontrahent_status');    
        }
        catch(PDOException $e){
            echo "nie jest git 2 :" . $e->getMessage();
        }

    return $records_checked;
};

?>