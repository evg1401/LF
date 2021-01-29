<?php
return [
    'home' => ['controller' => 'indexController', 'action' => 'index'],
    'registration' => ['controller' => 'Auth/AuthController', 'action' => 'registration'],
    'login' => ['controller' => 'Auth/AuthController', 'action' => 'login'],
    'passwordRecovery' => ['controller' => 'Auth/AuthController', 'action' => 'passwordRecovery'],
];
