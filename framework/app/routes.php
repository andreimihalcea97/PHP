<?php

//namespace App;

$routes = [
    '/' => ['controller' => 'HomeController',
            'action' => 'homeAction'],
    '/page/about-us' => ['controller' => 'PageController',
        'action' => 'aboutUsAction'],
    '/user/{id}' => ['controller' => 'UserController',
        'action' => 'showAction']
];

?>
