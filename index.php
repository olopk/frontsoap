<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MF Soap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Gamja+Flower" rel="stylesheet">
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

     <link rel="stylesheet" type="text/css" media="screen" href="style.css" />

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="JS/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
</head>
<body>
        <?php

        if(isset($_POST['submit']) && $_POST['submit'] == 'send'){
            if($_POST['email'] == 'olo@olo.olo' && $_POST['password'] == 'olo'){
                 $_SESSION['logged'] = true;
            }  
         }
        if(!isset($_SESSION['logged']) || $_SESSION['logged'] == false){
            include_once('login.php');            
        }
        else if(!isset($_GET['page'])){
            include_once('data.php');
        }
        else{
            switch($_GET['page']) {
                case 'settings':
                    include_once('settings.php');
                    break;
                case 'logout':
                    $_SESSION['logged'] = false;
                    header("Location: /frontsoap/index.php");
                    break;
                default:
                    include_once('data.php');                     
            }
        }
        ?> 
    
</body>
</html>