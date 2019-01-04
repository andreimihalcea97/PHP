<?php

//namespace App;

$routes = [
    '/' => ['controller' => 'HomeController',
            'action' => 'homeAction'],
    '/pages/about_us.php' => ['controller' => 'PageController',
        'action' => 'aboutUsAction'],
    '/user/{id}' => ['controller' => 'UserController',
        'action' => 'showAction'],
    '/login.php' => ['controller' => 'LoginController',
        'action' => 'logInAction']
];

?>
