<?php

class Parameter {

    public static $States = array(
        'PREREQUISITE_NAME' => 0,
        'PREREQUISITE_NO_NAME' => 1,
        'NO_PREREQUISITE_NAME' => 2,
        'NO_PREREQUISITE_NO_NAME' => 3
    );

    private $Prerequisite;
    private $Name;

    public function __construct($parameter) {
        list($this->Prerequisite, $this->Name) = explode(":", $parameter) + array_fill(0,2,null);
    }

    public function getPrerequisite() {
        return $this->Prerequisite;
    }

    public function getName() {
        return $this->Name;
    }

    public function getState() {
        if ($this->Prerequisite && $this->Name) {
            return self::$States['PREREQUISITE_NAME'];
        }
        else if ($this->Prerequisite && !$this->Name) {
            return self::$States['PREREQUISITE_NO_NAME'];
        }
        else if (!$this->Prerequisite && $this->Name) {
            return self::$States['NO_PREREQUISITE_NAME'];
        }
        else if (!$this->Prerequisite && !$this->Name) {
            return self::$States['NO_PREREQUISITE_NO_NAME'];
        }
    }
}

?>
