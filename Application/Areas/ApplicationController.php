<?php
require_once "Framework/Core/Controller.php";
require_once "Framework/Manager/DatabaseManager.php";
require_once "Application/Shared/Interface/MainView.php";

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
