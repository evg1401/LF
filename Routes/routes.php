<?php

return [
    'index' => ['controller' => 'indexController', 'action' => 'index'],
    'registration' => ['controller' => 'Auth/AuthController', 'action' => 'registration'],
    'login' => ['controller' => 'Auth/AuthController', 'action' => 'login'],
    'logout' => ['controller' => 'Auth/AuthController', 'action' => 'logout'],
    'passwordRecovery' => ['controller' => 'Auth/AuthController', 'action' => 'passwordRecovery'],
];