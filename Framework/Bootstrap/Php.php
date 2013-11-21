<?php
chdir('../../');

require_once "./Framework/Router.php";

header('Content-type: text/html; charset=utf-8');

/** Dynamic HTML minification
 *  decreases performance by 1-3ms, and probably isn't giving it back in load time anyways...
 *  But it looks pretty! **/
ob_start(function($buffer) {
    // 1 - after tags, 2 - before tags, 3 - multiple whitespace, 4 - after <script> tags, 5 - before </script> tags
    $search = array('/\>[^\S\n]+/s','/[^\S\n]+\</s','/(\s)+/s','/\<script\>[^\S]+/s','/[^\S]+\<\/script\>/s');
    $replace = array('>','<','\\1','<script>','</script>');
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
});

$uri_params = explode("/", $_GET["uri"]);
if (end($uri_params) == "") {
    array_pop($uri_params);
}

if (Router::CalculateMapping($uri_params)) {
    $rootArea = "";
    foreach ($_GET["area"] as $area) {
        $rootArea .= "/".$area;
    }
    define('ROOTAREA', $rootArea);

    $controllerName = ucwords($_GET["controller"]);
    $actionName = ucwords($_GET["view"]);
    $controllerFile = "./Application".ROOTAREA."/Controller/Component/".$controllerName."Controller.php";

    if (file_exists($controllerFile)) {
        require $controllerFile;
        $controller = $controllerName."Controller";
        $app = new $controller();
        $app->onActionExecuting();
        $result = $app->$actionName();
        $app->onActionExecuted();
        $app->onResultExecuting();
        if (method_exists($result, "Execute")) {
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
