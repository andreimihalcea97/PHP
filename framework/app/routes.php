<?php
$routes = [
    '/signup/' => ['controller' => 'UserController', 'action' => 'signupAction'],
    '/signupdone/' => ['controller' => 'UserController', 'action' => 'signupDoneAction'],
    '/login/' => ['controller' => 'UserController', 'action' => 'loginAction'],
    '/home/' => ['controller' => 'PageController', 'action' => 'goBackHomeAction'],
    '/user/go-to-store' => ['controller' => 'UserController', 'action' => 'goToStore'],
    '/user/go-to-profile' => ['controller' => 'UserController', 'action' => 'showAction'],
    '/user/edit-profile' => ['controller' => 'UserController', 'action' => 'showAction', 'guard' => 'Authenticated'],
    '/' => ['controller' => 'PageController', 'action' => 'homeAction'],
    '/page/about-us' => ['controller' => 'PageController', 'action' => 'aboutUsAction'],
    '/user/{id}' => ['controller' => 'UserController', 'action' => 'showAction'],
    '/user/edit/{id}' => ['controller' => 'UserController', 'action' => 'showAction', 'guard' => 'Authenticated']
];
