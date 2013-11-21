<?php

class ViewResult {

    private $Model;
    private $View;
    private $MasterName;

    public function __construct($model, $view, $masterName) {
        $this->Model = $model;
        $this->View = $view;
        $this->MasterName = $masterName;
    }

    public function Execute() {
        require "./Application/Shared/".$this->MasterName.".php";
        new $this->MasterName($this->Model, $this->View);
    }
}


?>
