<?php
require_once "Framework/Manager/InfoManager.php";

class AppInfoManager extends InfoManager
{
    private $Config;

    public function __construct($config) {
        $this->Config = $config;

        $this->InfoList[-1]  = new InfoModel("Unknown error.", 4);

    }
    
    public function HasInfo() {
        if (isset($this->Info) && $this->Config->InfoLevel >= $this->Info->InfoLevel) {
            return true;
        } else {
            return false;
        }
    }
}

?>
