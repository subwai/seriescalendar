<?php
namespace Framework\Result;

class ViewResult implements Result {

    private $View;
    private $ViewName;
    private $ControllerName;
    private $MasterView;
    private $MasterModel;

    public function __construct($view, $viewName, $controllerName, $masterView, $masterModel) {
        $this->View = $view;
        $this->ViewName = $viewName;
        $this->ControllerName = $controllerName;
        $this->MasterView = $masterView;
        $this->MasterModel = $masterModel;
    }

    public function Execute() {
        include "./Application/Shared/".$this->MasterView.".php";
    }
}


?>
