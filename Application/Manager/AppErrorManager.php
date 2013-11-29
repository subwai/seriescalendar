<?php
require_once "Framework/Manager/ErrorManager.php";

class AppErrorManager extends ErrorManager
{
    private $Config;

    public function __construct($config) {
        $this->Config = $config;

        $this->ErrorList[-1]  = new ErrorModel("Unknown error.", 4);
    }

    public function HasError() {
        if (isset($this->Error) && $this->Config->ErrorLevel >= $this->Error->ErrorLevel) {
            return true;
        } else {
            return false;
        }
    }
}

?>
