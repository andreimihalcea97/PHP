<?php

// namespace Framework;

// use \App\Controllers\HomeController;
// use \App\Controllers\PageController;
// use \App\Controllers\UserController;

class Router
{

    public function checkUrl(array $routes, string $url, string $param):void{
        
        $url_array = explode("/", $url, 5);
        $controller_url = "/" . end($url_array);
        echo($controller_url);

        if(isset($routes[$controller_url])) {
            list($controllerObj, $action) = $this->getControllerName($routes, $controller_url);
            $controllerObj->{$action}();
        }
        else if(preg_match('/\d+/', $url, $id))
        {   
            $index_url = explode("/", $url);
            $index = end($index_url);
            list($controllerObj, $action) = $this->getControllerName($routes, end($url_array), $id[0]);
            $controllerObj->{$action}($index);
        }
        else{
            echo "404 Not Found";
        }
    }

    public function getControllerName(array $routes, string $url,int $id=null)
    {   
        if($id){
            $copy_url = "/" . str_replace($id,"{id}",$url);
            $controller = $routes[$copy_url]['controller'];
            require_once "../app/controllers/" .$controller.".php";
            $controllerObj = new $controller();
            $action = $routes[$copy_url]['action'];
            return array($controllerObj, $action);
        }
        else{
            $controller = $routes[$url]['controller'];
            require_once "../app/controllers/" .$controller.".php";
            $controllerObj = new $controller();
            $action = $routes[$url]['action'];
            return array($controllerObj, $action);
        }

    }
}

?>