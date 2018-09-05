<?php
require_once(__DIR__ . '/class/Database.php');
require_once(__DIR__ . '/class/User.php');
require_once(__DIR__ . '/config/config.php');

session_start();

// Database app object
$db = new \Database\Database($db['driver'], $db['host'], $db['name'], $db['user'], $db['pass']);


// Source database object


try
{
    $conn = new PDO("dblib:host=192.168.0.61:1433;dbname=UBOJNIA_Sp__ka_jawna;charset=CP1250", 'sa', '');
}
catch(PDOException $e)
{
    echo "Exception: " . $e->getMessage();
}

$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$query="SELECT kh_Symbol, adr_Nazwa, adr_NIP FROM kh__Kontrahent INNER JOIN adr__Ewid ON kh_id = adr_IdObiektu and adr_TypAdresu=1";

// User object initialization
$user = new \User\User($db);

if(isset($_GET['page'])) {
    if ($_GET['page'] == 'logout') {
        $_SESSION['logged'] = false;
    }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF8">
    <meta title="Aplikacja MFSoap">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

    <script type="text/javascript">
        $(document).ready( function () {
            $('.table').DataTable(
                {
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Polish.json'
                    }
                }
            );
        } );
    </script>
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