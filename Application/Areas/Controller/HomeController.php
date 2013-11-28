<?php
require_once "./Application/Areas/ApplicationController.php";

class HomeController extends ApplicationController {

    function Index() {
        
        return $this->View();
    }
}

?>
