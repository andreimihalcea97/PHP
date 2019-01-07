<?php

namespace Framework;

// use \App\Controllers\HomeController;
//use App\Controllers\PageController;
//use App\Controllers\UserController;

class Router
{
    private $routes;
    private $url;
    private $param;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
   
    public function getResource(string $url, string $param)
    {
        $this->url = $url;
        $this->param = $param;
    }

    public function checkUrl():void{
        
        //$url_array = explode("/", $url, 5);
        //$controller_url = "/" . end($url_array);
        echo $this->url;

        if(preg_match('/login/', $this->url))
        {
            list($controllerObj, $action) = $this->getControllerName('login');
            $controllerObj->{$action}();
            return;
        }

        if(preg_match('/signupdone/', $this->url))
        {
            list($controllerObj, $action) = $this->getControllerName('signup');
            $controllerObj->{$action}();
            return;
        }

        if(isset($this->routes[$this->url])) {
            list($controllerObj, $action) = $this->getControllerName();
            $controllerObj->{$action}();
            return;
        }
        // else if(preg_match('/login?', $this->url))
        // {
        //     echo 'aici';
        // }
        // else if(preg_match('/\d+/', $this->url, $id))
        // {
        //     $index_url = explode("/", $url);
        //     $index = end($index_url);
        //     list($controllerObj, $action) = $this->getControllerName($this->routes, $this->url, $id[0]);
        //     $controllerObj->{$action}($index);
        // }
        echo "404 Not Found";
    }

    public function getControllerName(string $flag=null)
    {   
        if($flag == 'login' or $flag =='signup')
        {
            $controllerUrl = explode('?', $this->url);
            $controller = $this->routes[$controllerUrl[0]]['controller'];
            $action = $this->routes[$controllerUrl[0]]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action);
        }

        $controller = $this->routes[$this->url]['controller'];
        $action = $this->routes[$this->url]['action'];
        $controllerName = 'App\\Controllers\\' . $controller;
        $controllerObj = new $controllerName;
        return array($controllerObj, $action);
        // if($id){
        //     $copy_url = "/" . str_replace($id,"{id}",$url);
        //     $controller = $this->routes[$copy_url]['controller'];
        //     $action = $this->routes[$copy_url]['action'];
        //     # NAMESPACES?
        //     //$reflectionClass = new \ReflectionClass('App\\Controllers\\' . $controller);
        //     $controllerName = 'App\\Controllers\\' . $controller;
        //     $controllerObj = new $controllerName;
        //     //$controllerObj = $reflectionClass->newInstance();
        //     return array($controllerObj, $action);
        // }
        // else{
        //     $controller = $this->routes[$url]['controller'];
        //     $action = $this->routes[$url]['action'];
        //     $reflectionClass = new \ReflectionClass('App\\Controllers\\' . $controller);
        //     $controllerObj = $reflectionClass->newInstance();
        //     return array($controllerObj, $action);
        // }
    }
}

?>