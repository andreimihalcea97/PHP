<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <form action="/framework/public/index.php/login" method="post">
        <input type="submit" value="Go to Login">
    </form>
    <form action="/framework/public/pages.php" method="post">
        <input type="submit" value="Pages">
    </form>
</body>
</html>

<?php
//require __DIR__ . '/../vendor/autoload.php';

require_once "../app/config.php";
require_once "../src/Router.php";
require_once "../app/routes.php";

// use \App\config;
// use \App\routes;
// use \Framework\Router;

$config = new Config();
ini_set("error_log",__DIR__."/../logs/error.log");
error_reporting(E_ALL);
ini_set("display_errors", 0);

if($config->$_ENV == "dev"){
    ini_set("display_errors", 1);
}

$router = new Router();
$router->checkUrl($routes, $_SERVER["REQUEST_URI"], $_SERVER["QUERY_STRING"]);

?> 