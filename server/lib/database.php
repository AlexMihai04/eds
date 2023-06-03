<?php
    class db{
        // CREDITALS FOR THE DATABASE LOGIN
        public $svname;
        public $username;
        public $pass;
        public $name;

        public $conn;

        // PARSE THE DATABASE CONFIG CREDITALS AND SAVE THEM
        function parse_config($cfg){
            $this->svname = $cfg["servername"];
            $this->username = $cfg["username"];
            $this->pass = $cfg["password"];
            $this->name = $cfg["database_name"];
        }

        // CREATE DATABASE INSTANCE
        function connect()
        {
            $conn = new PDO("mysql:host=$this->svname;dbname=$this->name", $this->username, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = $conn;
        }

        // CLOSE THE DATABASE INSTANCE
        function close()
        {
            $this->conn = null;
        }

        // RETURNS THE DATABASE INSTANCE
        function get_instance(){
            return $this->conn;
        }
    }
?>