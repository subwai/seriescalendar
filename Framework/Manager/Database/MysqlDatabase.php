<?php

class MysqlDatabase {

    private $Reference;
    private $Name;
    private $Connection;

    function __construct($reference, $name) {
    
        $this->Reference = $reference;
        $this->Name = $name;
    }

    public function getReference() {
        return $this->Reference;
    }

    public function getName() {
        return $this->Name;
    }

    public function getConnection($connectionList) {
        $values = array_filter($connectionList, array($this, 'matchesConnection'));
        return reset($values);
    }

    public function getConnectionID($connectionList) {
        $values = array_filter($connectionList, array($this, 'matchesConnection'));
        return key($values);
    }

    private function matchesConnection($connection) {
        return ($connection->getName() == $this->Reference);
    }
}

?>
