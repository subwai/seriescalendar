<?php

class Connection {

    private $Name;
    private $Hostname;
    private $Username;
    private $Password;
    private $Databases = array();

    function __construct($array) {
        $this->Name = $array["Name"];
        $this->Hostname = $array["Hostname"];
        $this->Username = $array["Username"];
        $this->Password = $array["Password"];
        $this->Databases = $array["Databases"];
    }

    public function getName() {
        return $this->Name;
    }

    public function getDatabases() {
        return $this->Databases;
    }

    public function Connect($db) {
        return new PDO(sprintf("mysql:host=%s;dbname=%s;charset=utf8", $this->Hostname, $db), $this->Username, $this->Password);
    }
}

?>
