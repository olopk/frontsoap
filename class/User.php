<?php

namespace User;


class User
{

    public function login($login, $pass){

        try{
            $conn = new PDO("mysql:host=localhost;dbname=aplikacja", 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $q = "SELECT * FROM users WHERE login = '".$login."' and password = '".$pass."'";
            $q = "SELECT login, password FROM users WHERE login = '".$login."'";
            $records = $conn->query($q);
            if($records->rowCount() > 0){
                $fetch = $records->fetchAll();
                if($fetch[0]['password'] == md5($pass)){
                    $logged = true;
                    return true;
                }
                else{
                    echo '<div class="alert alert-danger">B³êdne has³o dla u¿ytkownika '.$login.'" !</div>"';
                    return false;
                }
            }
            else{
                echo '<div class="alert alert-danger">Brak takiego u¿ytkownika w systemie !</div>"';
                return false;
            }
        }
        catch(PDOException $e){
            echo "Exception: " . $e->getMessage();
        }
    }


}