<?php
//$dbdriver = 'mysql';
//$servername = 'localhost';
//$dbname = 'subiekt';
//$username = 'root';
//$password = '';
//$col_nip = 'nip';
//$col_contractor = 'nazwa';
//$wsdl = 'http://localhost/soap/wsdl.xml';
$dbdriver = '';
$servername = '';
$dbname = '';
$username = '';
$password = '';
$col_nip = '';
$col_contractor = '';
$wsdl = 'http://localhost/soap/wsdl.xml';
$testowa = "cos tam";
echo $testowa;
credentialsLoad();
echo $testowa;
function credentialsLoad(){
    //this function will check if there are the credentials to collect the data from the user DB, if there are the app will run correctly.
    try{
        $conn = new PDO("mysql:host=localhost;dbname=aplikacja", 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cred = $conn->query("SELECT * from db_credentials")->fetchAll();

        global $dbdriver;
        global $servername;
        global $username;
        global $password;
        global $dbname;
        global $col_nip;
        global $col_contractor;

        if(count($cred)>0){
            $dbdriver = $cred[0]['dbdriver'];
            $servername = $cred[0]['servername'];
            $username = $cred[0]['dblogin'];
            $password = $cred[0]['dbpass'];
            $dbname = $cred[0]['dbname'];
            $col_nip = $cred[0]['col_nip'];
            $col_contractor = $cred[0]['col_kon'];
            return true;
        }
        else{
            return false;
        }


    }
    catch(PDOException $e){
        echo "Cannot load the credentials. Error code: " . $e->getMessage();
        return false;

    }
}
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
function credentialsSave($driver, $servername, $login, $pass, $dbname, $tbname, $col_nip, $col_contractor){
    try{
        //try to connect using the user input credentials
        $conn = new PDO("$driver:host=$servername;dbname=$dbname", $login, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //try to retrieve data using the tbname and colnames inputed by user
        $q = "SELECT ".$col_nip." AND ".$col_contractor." FROM ".$tbname.";" ;
        $records = $conn->query($q);
        //i dont know if there is any sense but i decided to close the connection with the db above.
        $conn = null;
        //i open a new connection to save the credentials in the app db.
        $conn = new PDO("mysql:host=localhost;dbname=aplikacja", 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //prepare a quer to clear the eventual existing record in the db and concatenate it to the query that will insert the new values.
        $i="DELETE FROM db_credentials; ";
        $i.="INSERT INTO db_credentials (dbdriver, servername, dblogin, dbpass, dbname, tbname, col_nip, col_kon) VALUES ('".$driver."', '".$servername."', '".$login."', '".$pass."', '".$dbname."', '".$tbname."', '".$col_nip."', '".$col_contractor."')";
        //run the query.
        $insert = $conn->query($i);

        return true;
    }
    catch(PDOException $e){
        echo "cos nie bangla : " . $e->getMessage();
        return false;
    }
}
?>