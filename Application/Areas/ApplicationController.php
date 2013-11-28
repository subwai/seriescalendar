<?php
require_once "./Framework/Controller.php";
require_once "./Framework/Manager/DatabaseManager.php";
require_once "./Application/Shared/Interface/MainView.php";
require_once "./Application/Manager/AppErrorManager.php";
require_once "./Application/Manager/AppInfoManager.php";
require_once "./Application/Manager/FacebookManager.php";
require_once "./Application/Extra/facebook-php-sdk/facebook.php";

class ApplicationController extends Controller {
    
    protected $StartTime;

    public function __construct() {
        $this->StartTime = microtime(true);
    }

    public function onActionExecuting() {

    }

    public function onActionExecuted() {
        
    }
}

?>
