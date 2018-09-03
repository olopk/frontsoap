<?php
require_once(__DIR__ . '/class/Database.php');
require_once(__DIR__ . '/class/User.php');
require_once(__DIR__ . '/config/config.php');

session_start();

// Database object initialization
$db = new \Database\Database($db['driver'], $db['host'], $db['name'], $db['user'], $db['pass']);

// doesnt work
ini_set('mssql.charset', 'CP1250');

try
{
    $conn = new PDO("dblib:host=192.168.1.104:1433;dbname=UBOJNIA_Sp__ka_jawna;charset=UTF-8", 'sa', '');
}
catch(PDOException $e)
{
    echo "Exception: " . $e->getMessage();
}

$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$query="SELECT kh_Symbol, adr_Nazwa, adr_NIP FROM kh__Kontrahent INNER JOIN adr__Ewid ON kh_id = adr_IdObiektu and adr_TypAdresu=1";

// simple query
/*
$stmt = $conn->query( $query );
while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){
    print_r( $row['kh_Symbol'] ."\n" );
}
*/

// User object initialization
$user = new \User\User($db);

if ($_GET['page'] == 'logout') {
    $_SESSION['logged'] = false;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF8">
    <meta title="Aplikacja MFSoap">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>


<?php

if ($_POST['submit'] == 'send') {
    $_SESSION['logged'] = $user->login($_POST['login'], $_POST['pass']);
}
if ($_SESSION['logged'] == false) {
    @include('header.php');
    @include_once('login.php');
} else {
    @include('header.php');
    if ($_GET['page'] == 'settings') {
        @include('settings.php');
    } else {
        @include_once('dashboard.php');
    }
}
?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>