<?php
require_once "Framework/Core/Controller.php";
require_once "Framework/Manager/DatabaseManager.php";
require_once "Application/Shared/Interface/MainView.php";
require_once "Application/Manager/AppErrorManager.php";
require_once "Application/Manager/AppInfoManager.php";
require_once "Application/Manager/FacebookManager.php";
require_once "Application/Extra/facebook-php-sdk/facebook.php";

class ApplicationController extends Controller
{
    public $DatabaseMgr;
    public $ErrorMgr;
    public $InfoMgr;
    
    protected $StartTime;

    public function __construct() {
        $this->StartTime = microtime(true);
        $ini = parse_ini_file("../seriescalendar.ini", true);
        parent::__construct($ini["application"]);

        $this->ErrorMgr = new AppErrorManager($this->Config);
        $this->InfoMgr = new AppInfoManager($this->Config);
        $this->DatabaseMgr = new DatabaseManager($ini, $this->ErrorMgr);
    }

    public function onActionExecuting() {
        session_start();
        FacebookManager::setFacebookInstance(new Facebook(array(
          'appId'  => '362447850567375',
          'secret' => 'b86e71eb6836e0c24442bb4ffbb6466f',
        )));
        FacebookManager::setFacebookUser(FacebookManager::FacebookInstance()->getUser());

    }

    public function onActionExecuted() {
        
    }
}

?>
