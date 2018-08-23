<?php

namespace Database;

class Database
{

    private $db_driver;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;

    function __construct($driver, $host, $name, $user, $pass)
    {
        $this->db_driver = $driver;
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_user = $user;
        $this->db_pass = $pass;
    }

    function connect()
    {
        try
        {
            $dsn = $this->db_driver.':host='.$this->db_host.';dbname='.$this->db_name;
            $conn = new PDO($dsn, $this->db_user, $this->db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Exception: " . $e->getMessage();
        }
    }


}

