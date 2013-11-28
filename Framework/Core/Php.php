<?php
chdir('../../');

require_once "./Framework/Router.php";

header('Content-type: text/html; charset=utf-8');

$uri_params = explode("/", $_GET["uri"]);
if (end($uri_params) == "") {
    array_pop($uri_params);
}

if (Router::CalculateMapping($uri_params)) {
    $rootArea = "/Areas";
    foreach ($_GET["area"] as $area) {
        $rootArea .= "/".ucwords($area);
    }
    define('ROOTAREA', $rootArea);

    $controllerName = ucwords($_GET["controller"]);
    $actionName = ucwords($_GET["view"]);
    $controllerFile = "./Application".ROOTAREA."/Controller/".$controllerName."Controller.php";
    
    if (file_exists($controllerFile)) {
        require $controllerFile;
        $controller = $controllerName."Controller";
        $app = new $controller();
        $app->onActionExecuting();
        $result = $app->$actionName();
        $app->onActionExecuted();
        $app->onResultExecuting();
        if ($result instanceof Result) {
            $result->Execute();
        }
        $app->onResultExecuted();
    }  else {
        Router::Error(404, "Controller", $controllerName);
    }
} else {
    Router::Error(404, "Route for", $_GET["uri"]);
}

?>
