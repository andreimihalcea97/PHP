<?php
$routes = [
    '/login' => ['controller' => 'UserController', 'action' => 'loginAction'],
    '/' => ['controller' => 'PageController', 'action' => 'homeAction'],
    '/page/about-us' => ['controller' => 'PageController', 'action' => 'aboutUsAction'],
    '/user/{id}' => ['controller' => 'UserController', 'action' => 'showAction'],
    '/user/edit/{id}' => ['controller' => 'UserController', 'action' => 'showAction', 'guard' => 'Authenticated']
];
