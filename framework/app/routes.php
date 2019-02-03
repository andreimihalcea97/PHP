<?php
$routes = [
    '/signup/' => ['controller' => 'UserController', 'action' => 'signupAction'],
    '/signupdone/' => ['controller' => 'UserController', 'action' => 'signupDoneAction'],
    '/login/' => ['controller' => 'UserController', 'action' => 'loginAction'],
    '/home/' => ['controller' => 'PageController', 'action' => 'goBackHomeAction'],
    '/user/go-to-store' => ['controller' => 'GameController', 'action' => 'goToStore'],
    '/user/go-to-profile' => ['controller' => 'UserController', 'action' => 'showAction'],
    '/user/edit-profile' => ['controller' => 'UserController', 'action' => 'showAction', 'guard' => 'Authenticated'],
    '/user/sumadd' => ['controller' => 'UserController', 'action' => 'addFundsDone'],
    '/user/add-wallet' => ['controller' => 'UserController', 'action' => 'addFunds'],
    '/' => ['controller' => 'PageController', 'action' => 'homeAction'],
    '/buygame/' => ['controller' => 'GameController', 'action' => 'buyGameAction'],
    '/page/about-us' => ['controller' => 'PageController', 'action' => 'aboutUsAction'],
    '/user/{id}' => ['controller' => 'UserController', 'action' => 'showAction'],
    '/user/edit/{id}' => ['controller' => 'UserController', 'action' => 'showAction', 'guard' => 'Authenticated']
];