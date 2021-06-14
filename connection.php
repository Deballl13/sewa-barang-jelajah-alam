<?php

class Connection{

    private $conn;

    public function __construct(){
        $this->conn = new mysqli("localhost", "root", "", "oreivasia");    

        if($this->conn->connect_errno > 0) {
            echo "connection time out";
        }
    }

    public function getConn(){
        return $this->conn;
    }




}