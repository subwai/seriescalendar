<?php
require_once "Framework/Helper/FrameworkHelper.php";
require_once "Framework/Core/Route.php";

class Router {
    private static $routes;

    public static function Register($route, $defaults = array()) {
        self::$routes[] = new Route($route, $defaults);
    }

    // Semi specific, both application / framework will want to use this. Place HttpError folder in root?
    public static function Error($code, $type, $name) {
        try {
            $_GET["HttpErrorType"] = $type;
            $_GET["HttpErrorName"] = $name;
            include "./Application/HttpError/".$code.".php";
        } catch (Exception $e) {
            printf("%s - %s: %s, does not exist!", $code, $type, $name);
        }
        exit;
    }

    public static function DefineArea() {
        $rootArea = "Areas";
        foreach ($_GET["area"] as $area) {
            $rootArea .= "/".ucwords($area);
        }
        define('ROOTAREA', $rootArea);
    }

    public static function CalculateMapping($request_params) {
        require "Application/routes.php";

        $map = self::FetchMapFromMatchingRoute($request_params);

        if (empty($map) && count($request_params) > 0) {
            return false;
        }

        if (isset($map["controller"]) && $map["controller"] == "") unset($map["controller"]);
        if (isset($map["controller"])) $map["controller"] = str_replace("-", "_", $map["controller"]);
        if (isset($map["action"]) && $map["action"] == "") unset($map["action"]);
        if (isset($map["action"])) $map["action"] = str_replace("-", "_", $map["action"]);
        
        $defaultMap = array(
            "area" => array(),
            "controller" => "home",
            "action" => "index"
        );

        $_GET = array_merge($_GET, $map + $defaultMap);
        return true;
    }

    private static function FetchMapFromMatchingRoute($request_params) {
        foreach (self::$routes as $route) {
            $map = array();

            if ($route->paramCountMatch($request_params)) { 
                foreach ($route->getParameters() as $param_key => $param_container) {
                    $match = false;
                    foreach ($param_container as $param) {
                        if (self::EvaluateParameter($param, $request_params[$param_key], $map)) {
                            $match = true;
                        }
                    }
                    
                    if (!$match) {
                        continue 2;
                    }
                }

                foreach ($route->getAllDefaults() as $name => $value) {
                    if (!isset($map[$name])) {
                        $map[$name] = $value;
                    }
                }

                return $map;
            }
        }
        return array();
    }

    private static function EvaluateParameter($param, $current_request_param, &$map) {
        
        switch ($param->getState()) {

            case Parameter::$States['PREREQUISITE_NAME']:

                if ($param->getPrerequisite() == $current_request_param) {
                    if ($param->getName() == "area") {
                        $map["area"][] = $current_request_param;
                    } else {
                        $map[$param->getName()] = $current_request_param;
                    }
                } else {
                    return false;
                }

                break;

            case Parameter::$States['PREREQUISITE_NO_NAME']:

                if ($param->getPrerequisite() != $current_request_param) {
                    return false;
                }

                break;

            case Parameter::$States['NO_PREREQUISITE_NAME']:

                # The requested URI has a NON-NULL value for that parameter - set map to that value
                if (!is_null($current_request_param)) {
                    $map[$param->getName()] = $current_request_param;
                }
                # The requested URI has a NULL value for that parameter - set map to default value
                else if ($route->hasDefaultFor($param->getName())) {
                    $map[$param->getName()] = $route->getDefaultFor($param->getName());
                }

                break;
            
            default:
                return false;
        }

        return true;
    }
}
?>
