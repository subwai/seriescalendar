<?php

class JsonResult {

    private $Model;

    public function __construct($model) {
        $this->Model = $model;
    }

    public function Execute() {
        echo json_encode($this->Model);
    }
}


?>
