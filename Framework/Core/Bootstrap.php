<?php
chdir('../../');

require_once "Framework/Core/Router.php";

$uri_params = explode("/", $_GET["uri"]);
if (end($uri_params) == "") {
    array_pop($uri_params);
}

if (Router::CalculateMapping($uri_params)) {
    Router::DefineArea();

    $controllerName = ucwords($_GET["controller"]);
    $actionName = ucwords($_GET["action"]);

    $controllerFile = sprintf("Application/%s/Controller/%s.php", ROOTAREA, $controllerName);
    if (is_file($controllerFile)) {
        require $controllerFile;
        $controllerClass = "Controller\\".$controllerName;
        $controller = new $controllerClass();
        $controller->onActionExecuting();
        $result = $controller->$actionName();
        $controller->onActionExecuted();
        $controller->onResultExecuting();
        if ($result instanceof Result) {
            $result->Execute();
        }
        $controller->onResultExecuted();
    }  else {
        Router::Error(404, "Controller", $controllerName);
    }
} else {
    Router::Error(404, "Route for", $_GET["uri"]);
}

?>
