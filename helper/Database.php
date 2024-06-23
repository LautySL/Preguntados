<?php

class Database
{
    private $conn;

    public function __construct($servername, $username, $dbname, $password)
    {
        $this->conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            error_log("MySQL Error: " . mysqli_error($this->conn));
            return false;
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function execute($sql)
    {
        return mysqli_query($this->conn, $sql);
    }

    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function begin_transaction()
    {
        mysqli_begin_transaction($this->conn);
    }

    public function commit()
    {
        mysqli_commit($this->conn);
    }

    public function rollback()
    {
        mysqli_rollback($this->conn);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}