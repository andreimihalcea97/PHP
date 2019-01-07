<?php

namespace Framework;

// use \App\Controllers\HomeController;
//use App\Controllers\PageController;
//use App\Controllers\UserController;

class Router
{
    private $routes;
    //private $url;
    //private $param;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
   
    public function getResource(string $url, string $param)
    {
        $this->url = $url;
        $this->param = $param;
    }

    public function checkUrl(string $url, string $param, array $routes):void{
        
        $url_array = explode("/", $url, 5);
        $controller_url = "/" . end($url_array);
        echo $controller_url;

        if(isset($this->routes[$controller_url])) {
            list($controllerObj, $action) = $this->getControllerName($this->routes, $controller_url);
            $controllerObj->{$action}();
        }
        else if(preg_match('/\d+/', $url, $id))
        {
            $index_url = explode("/", $url);
            $index = end($index_url);
            list($controllerObj, $action) = $this->getControllerName($this->routes, end($url_array), $id[0]);
            $controllerObj->{$action}($index);
        }
        else{
            echo "404 Not Found";
        }
    }

    public function getControllerName(array $routes, string $url, int $id=null)
    {   
        print_r($this->routes);
        die();
        if($id){
            $copy_url = "/" . str_replace($id,"{id}",$url);
            $controller = $this->routes[$copy_url]['controller'];
            $action = $this->routes[$copy_url]['action'];
            # NAMESPACES?
            //$reflectionClass = new \ReflectionClass('App\\Controllers\\' . $controller);
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            //$controllerObj = $reflectionClass->newInstance();
            return array($controllerObj, $action);
        }
        else{
            $controller = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
            $reflectionClass = new \ReflectionClass('App\\Controllers\\' . $controller);
            $controllerObj = $reflectionClass->newInstance();
            return array($controllerObj, $action);
        }
    }
}

?>