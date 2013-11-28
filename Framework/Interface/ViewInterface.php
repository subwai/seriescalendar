<?php

abstract class ViewInterface {
    protected $Model;
    public $MasterView = "Master";

    function __construct($model) {
        $this->Model = $model;
    }
}

?>