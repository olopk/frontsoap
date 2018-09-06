<?php
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/class/Database.php');
require_once(__DIR__ . '/class/Engine.php');

header('Content-Type: text/html; charset=utf-8');


try
{
//    $conn = new PDO("dblib:host=192.168.1.104:1433;dbname=UBOJNIA_Sp__ka_jawna;charset=CP1250", 'sa', '');
    $dbs = new \Database\Database($db['src']['driver'], $db['src']['host'], $db['src']['name'], $db['src']['user'], $db['src']['pass']);        // Database source
    $db = new \Database\Database($db['app']['driver'], $db['app']['host'], $db['app']['name'], $db['app']['user'], $db['app']['pass']);
}
catch(PDOException $e)
{
    echo "Exception: " . $e->getMessage();
}



$engine = new \Engine\Engine($dbs, $db, $wsdl);

echo '<pre>'; print_r($engine->process()); echo '</pre>';






function load(){

    global $dbdriver;
    global $servername;
    global $username;
    global $password;
    global $dbname;
    global $col_nip;
    global $col_contractor;
    global $wsdl;
    $records = '';
    $records_checked = '';

    //connection to the Subiect DB to retrieve the contractors data.
    try{
        $conn = new PDO("$dbdriver:host=$servername;dbname=$dbname", $username, $password);
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
            $response = $client->__soapCall("SprawdzNIP", array($record[$col_nip]));
            $array = json_decode(json_encode($response), True);
            // now we check if the contractor already exists in the db, if it is we only update his status.
            $c = "SELECT * FROM kontrahent_status WHERE nip='".$record[$col_nip]."';";
            $check = $conn->query($c);
            if(!$check->rowCount() > 0){
                $insert = "INSERT INTO kontrahent_status (nazwa, nip, kod, komunikat) VALUES ('".$record[$col_contractor]."', '".$record[$col_nip]."', '".$array['Kod']."', '".$array['Komunikat']."')";
                $conn->query($insert);
            }
            else{
                $update = "UPDATE kontrahent_status SET nazwa ='".$record[$col_contractor]."', kod = '".$array['Kod']."', komunikat = '".$array['Komunikat']."' WHERE nip='".$record[$col_nip]."';";
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