<?php
namespace Framework\Core {
    chdir('../../');

    $uri_params = explode("/", $_GET["uri"]);
    if (end($uri_params) == "") {
        array_pop($uri_params);
    }
    new Router();

    if (Router::CalculateMapping($uri_params)) {
        $rootArea = "\\Areas";
        foreach ($_GET["area"] as $area) {
            $rootArea .= "\\".ucwords($area);
        }
        define('ROOTAREA', $rootArea);

        $controllerName = ucwords($_GET["controller"]);
        $actionName = ucwords($_GET["view"]);
        $controller = "Application".ROOTAREA."\\Controller\\".$controllerName;
        
        if (class_exists($controller)) {
            $app = new $controller();
            $app->onActionExecuting();
            $result = $app->$actionName();
            $app->onActionExecuted();
            $app->onResultExecuting();
            if ($result instanceof \Framework\Result\Result) {
                $result->Execute();
            }
            $app->onResultExecuted();
        }  else {
            Router::Error(404, "Controller", $controllerName);
        }
    } else {
        Router::Error(404, "Route for", $_GET["uri"]);
    }
}

namespace {
    function __autoload($class) {
       require_once $class . '.php';
    }
}

?>
