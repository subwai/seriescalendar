<?php
namespace Application\Areas;

class ApplicationController extends \Framework\Core\Controller {
    
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
