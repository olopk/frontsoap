<?php

namespace Database;
use PDO;

class Database
{

    private $db_driver;
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;

    private $dbh;
    private $stmt;

    public function __construct($driver, $host, $name, $user, $pass)
    {
        $this->db_driver = $driver;
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_user = $user;
        $this->db_pass = $pass;

        $this->connect();
    }

    public function connect()
    {
        try
        {
            $dsn = $this->db_driver.':host='.$this->db_host.';dbname='.$this->db_name;
            $this->dbh = new PDO($dsn, $this->db_user, $this->db_pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Exception: " . $e->getMessage();
        }
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }


}

