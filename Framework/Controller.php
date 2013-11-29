<?php
require_once "Framework/Interface/ViewInterface.php";
require_once "Framework/Result/Result.php";
require_once "Framework/Result/ViewResult.php";
require_once "Framework/Result/JsonResult.php";

class Controller
{
    protected $Config;
    protected $DatabaseMgr;
    protected $ErrorMgr;
    protected $InfoMgr;
    protected $MasterModel;

    public function __construct() {
        $this->Config = parse_ini_file("quartz.ini", true);

        switch ($this->Config["application"]["environment"])
        {
            case 'development':
                error_reporting(E_ALL);
            break;
        
            case 'testing':
            case 'production':
                error_reporting(0);
            break;

            default:
                exit('The application environment is not set correctly.');
        }

        /** Dynamic HTML minification
         *  decreases performance by 1-3ms, and probably isn't giving it back in load time anyways...
         *  But it looks pretty! **/
        if (!$this->Config["application"]["compressed"]) {
            ob_start(function($buffer) {
                // 1 - after tags, 2 - before tags, 3 - multiple whitespace, 4 - after <script> tags, 5 - before </script> tags
                $search = array('/\>[^\S\r\n]{2,}/s','/[^\S\r\n]{2,}\</s','/(\s)+/s','/\<script\>[^\S]+/s','/[^\S]+\<\/script\>/s');
                $replace = array('>','<','\\1','<script>','</script>');
                $buffer = preg_replace($search, $replace, $buffer);
                return $buffer;
            });
        }
    }

    public function __call($method, $parameters) {
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

    public function View($model = array(), $viewName = null, $masterView = null) {
        $viewName = is_null($viewName) ? ucfirst($_GET["view"]) : $viewName;
        $controllerName = ucfirst($_GET["controller"]);

        $viewFile = "./Application";
        $viewFile .= ROOTAREA;
        $viewFile .= "/View";
        $viewFile .= "/".$controllerName;
        $viewFile .= "/".$viewName;
        $viewFile .= ".php";

        if (file_exists($viewFile)) {
            require $viewFile;
            $view = new $viewName($model);
            $masterView = is_null($masterView) ? $view->MasterView : $masterView;
            return new ViewResult($view, $viewName, $controllerName, $masterView, $this->MasterModel);
        } else {
            Router::Error(404, "View", $viewName);
        }        
    }

    public function Redirect($action, $controller = null, $args = null) {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https' : 'http';
        $controller = is_null($controller) ? $_GET["controller"] : $controller;
        $action = strtolower($action);
        $argStr = is_null($args) ? "" : implode("/", $args);
        header(sprintf("Location: %s://%s/%s/%s/%s", $protocol, $_SERVER["HTTP_HOST"], $controller, $action, $argStr));
    }

    public function Json($model = array(), $success = true) {
        header('Content-type: application/json');
        if (!$success) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }
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
