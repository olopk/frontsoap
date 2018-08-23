<?php

function loginCheck($login, $pass){
        
     try{
        $conn = new PDO("mysql:host=localhost;dbname=aplikacja", 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $q = "SELECT * FROM users WHERE login = '".$login."' and password = '".$pass."'";
        $q = "SELECT login, password FROM users WHERE login = '".$login."'";
        $records = $conn->query($q);
        if($records->rowCount() > 0){
            $fetch = $records->fetchAll();
//            var_dump($fetch);
            if($fetch[0]['password'] == md5($pass)){
                $logged = true;
                return true;
            }
            else{
                echo '<div class="alert alert-danger">Błędne hasło dla użytkownika '.$login.'" !</div>"';
                return false;
            }    
        }
         else{
             echo '<div class="alert alert-danger">Brak takiego użytkownika w systemie !</div>"';
             return false;
         }      
    }
    catch(PDOException $e){
        echo "Something goes wrong: " . $e->getMessage();
    }
}
?>