<?php

namespace User;


class User
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login($login, $pass)
    {

        try {
            $q = "SELECT login, password FROM users WHERE login = '" . $login . "'";
            $stmt = $this->db->query($q);

            //TODO: Check db integrity constraints if there's unique login
            $rst = $this->db->single();

            if ($rst['password'] == md5($pass)) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo "Exception: " . $e->getMessage();
        }
    }

}