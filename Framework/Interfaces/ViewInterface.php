<?php
namespace Framework\Interfaces;

abstract class ViewInterface {
    public $MasterView = "Master";

    protected $Model;

    function __construct($model) {
        $this->Model = $model;
    }
}

?>