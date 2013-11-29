<?php
namespace Framework\Core;

class Route {

    private $Parameters = array();
    private $Defaults;

    public function __construct($route, $defaults = array()) {
        $parameters = explode("/", $route);
        array_shift($parameters);
        foreach ($parameters as $param_key => $param_container) {
            $this->Parameters[$param_key] = array();
            foreach (explode(",", $param_container) as $param) {
                $this->Parameters[$param_key][] = new Parameter($param);
            }
        }
        
        $this->Defaults = $defaults;
    }

    public function getParameters() {
        return $this->Parameters;
    }

    public function paramCountMatch($params) {
        return count($this->Parameters) == count($params);
    }

    public function getDefaultFor($name) {
        return isset($this->Defaults[$name]) ? $this->Defaults[$name] : false;
    }

    public function getAllDefaults() {
        return $this->Defaults;
    }
}

?>
