<?php
chdir('../../');

require_once "Framework/Core/Router.php";

$uri_params = explode("/", $_GET["uri"]);
if (end($uri_params) == "") {
    array_pop($uri_params);
}

if (Router::CalculateMapping($uri_params)) {

    $controllerFile = sprintf("Application/%s/Controller/%s.php", ROOTAREA, CONTROLLER);
    if (is_file($controllerFile)) {
        require $controllerFile;
        $controllerClass = "Controller\\".CONTROLLER;
        $controller = new $controllerClass();
        $controller->onActionExecuting();
        $action = ACTION;
        $result = $controller->$action();
        $controller->onActionExecuted();
        $controller->onResultExecuting();
        if ($result instanceof Result) {
            $result->Execute();
        }
        $controller->onResultExecuted();
    }  else {
        Router::Error(404, "Controller", CONTROLLER);
    }
} else {
    Router::Error(404, "Route for", $_GET["uri"]);
}

?>
