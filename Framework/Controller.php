<?php
require_once "./Framework/Result/ViewResult.php";
require_once "./Framework/Result/JsonResult.php";

class Controller
{
    public $MasterModel;

    function __call($method, $parameters) {
        Router::Error(404, "Action", $method);
    }

    public function onActionExecuting() {
    }

    public function onActionExecuted() {
    } 

    public function onResultExecuting() {
    }   

    public function onResultExecuted() {
    }

    public function View($model = array(), $viewName = null, $masterName = null) {
        $viewName = is_null($viewName) ? ucfirst($_GET["view"]) : $viewName;
        $controllerName = ucfirst($_GET["controller"]);

        $viewFile = "./Application";
        $viewFile .= ROOTAREA;
        $viewFile .= "/View";
        $viewFile .= "/".$controllerName;
        $viewFile .= "/".$viewName;
        $viewFile .= ".php";

        if (file_exists($viewFile)) {
            FrameworkHelper::IncludeFiles("./Application/Shared/Interface");
            require $viewFile;
            $view = new $viewName($model);
            $interfaces = class_implements($view);
            $masterName = is_null($masterName) ? substr(reset($interfaces), 1) : $masterName;
            return new ViewResult($this->MasterModel, $view, $masterName);
        } else {
            Router::Error(404, "View", $viewName);
        }        
    }

    public function Json($model = array()) {
        header('Content-type: application/json');
        return new JsonResult($model);
    }

    public function FullView($model = array(), $viewName = null) {
        $viewName = is_null($viewName) ? ucfirst($_GET["view"]) : $viewName;
        $controllerName = ucfirst($_GET["controller"]);
        
        $viewFile = "./Application";
        $viewFile .= ROOTAREA;
        $viewFile .= "/View";
        $viewFile .= "/".$controllerName;
        $viewFile .= "/".$viewName;
        $viewFile .= ".php";

        if (file_exists($viewFile)) {
            $viewName = is_null($viewName) ? ucfirst($_GET["view"]) : $viewName;
            include $viewFile;
        }
    }
}
?>
