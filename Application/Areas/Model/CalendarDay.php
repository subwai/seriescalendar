<?php

class CalendarDay {
    public $name;
    public $series = array();
    public $class;
    public $active;

    public function __construct($name) {
        $this->name = $name;
    }

    public function addSerie($serie) {
        $this->series[] = $serie;
    }
}

?>