<?php
require_once "Application/Areas/ApplicationController.php";

class Home extends ApplicationController {

    function Index() {
        
        return $this->View();
    }
}

?>
