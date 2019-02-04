<?php

namespace Framework;

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
        
        if(preg_match('/buygame/', $this->url))
        {
            list($controllerObj, $action, $userID, $gameID) = $this->getControllerName('buygame');
            $controllerObj->{$action}($userID, $gameID);
            return;
        }

        if(preg_match('/add-wallet/', $this->url))
        {
            list($controllerObj, $action, $userID) = $this->getControllerName('addfunds');
            $controllerObj->{$action}($userID);
            return;
        }

        if(preg_match('/sumadd/', $this->url))
        {
            list($controllerObj, $action, $sum, $userID) = $this->getControllerName('addfundsdone');
            $controllerObj->{$action}($userID, $sum);
            return;
        }

        if(preg_match("/go-to-profile/", $this->url)){
            $index_url = explode("/", $this->url);
            $index = end($index_url);
            list($controllerObj, $action) = $this->getControllerName('user/profile');
            $controllerObj->{$action}($index);
            return;
        }

        if(preg_match("/go-to-store/", $this->url)){
            $index_url = explode("/", $this->url);
            $index = end($index_url);
            list($controllerObj, $action) = $this->getControllerName('user/store');
            $controllerObj->{$action}($index);
            return;
        }

        if(isset($this->routes[$this->url])) {
            list($controllerObj, $action) = $this->getControllerName();
            $controllerObj->{$action}();
            return;
        }

        list($controllerObj, $action) = $this->getControllerName("home");
        $controllerObj->{$action}();
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

        if($flag == "user/profile")
        {
            $controllerUrl = explode('/', $this->url, -1);
            $controllerUrl = implode('/', $controllerUrl);
            $controller = $this->routes[$controllerUrl]['controller'];
            $action = $this->routes[$controllerUrl]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action);
        }
        
        if($flag == "user/store")
        {   
            # get ut the index of the query string
            $controllerUrl = explode('/', $this->url, -1);
            # implode back the query string
            $controllerUrl = implode('/', $controllerUrl);
            $controller = $this->routes[$controllerUrl]['controller'];
            $action = $this->routes[$controllerUrl]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action);
        }

        if($flag == "buygame")
        {   
            # get ut the index of the query string
            $controllerUrl = str_replace("?", "", $this->url);
            $controllerUrl = explode('/', $controllerUrl);
            $userID = $controllerUrl[2];
            $gameID = $controllerUrl[3];
            $controllerUrl = "/" . $controllerUrl[1] . "/";
            $controller = $this->routes[$controllerUrl]['controller'];
            $action = $this->routes[$controllerUrl]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action, $userID, $gameID);
        }

        if($flag == "addfunds")
        {   
            # get userID
            $controllerUrl = str_replace("?", "", $this->url);
            $controllerUrl = explode('/', $controllerUrl);
            $userID = end($controllerUrl);
            # get controllerUrl
            $controllerUrl = explode('/', $this->url, -1);
            # implode back the query string
            $controllerUrl = implode('/', $controllerUrl);
            $controller = $this->routes[$controllerUrl]['controller'];
            $action = $this->routes[$controllerUrl]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action, $userID);
        }

        if($flag == "addfundsdone")
        {   
            # get sum
            $controllerUrl = explode("=", $this->url);
            $sum = end($controllerUrl);
            # get userID
            $controllerUrl = explode("/", $this->url);
            $userID = $controllerUrl[3];
            # get controllerUrl
            $controllerUrl = explode('/', $this->url, -2);
            # implode back the query string
            $controllerUrl = implode('/', $controllerUrl);
            $controller = $this->routes[$controllerUrl]['controller'];
            $action = $this->routes[$controllerUrl]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action, $sum, $userID);
        }

        if($flag == "home")
        {
            $controllerUrl = explode('?', $this->url);
            $controller = $this->routes["/home/"]['controller'];
            $action = $this->routes["/home/"]['action'];
            $controllerName = 'App\\Controllers\\' . $controller;
            $controllerObj = new $controllerName;
            return array($controllerObj, $action);
        }

        $controller = $this->routes[$this->url]['controller'];
        $action = $this->routes[$this->url]['action'];
        $controllerName = 'App\\Controllers\\' . $controller;
        $controllerObj = new $controllerName;
        return array($controllerObj, $action);
    }
}

?>