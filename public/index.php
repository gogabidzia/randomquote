<?php
require_once '../vendor/autoload.php';
define(BASE_PATH, dirname(__DIR__));
$routes = [
    '/'      => [
        'controller' => \Gogabidzia\RandomQuote\Controllers\GeneratorController::class,
        'action'     => 'index',
    ],
    '/image' => [
        'controller' => \Gogabidzia\RandomQuote\Controllers\GeneratorController::class,
        'action'     => 'image',
    ],
];
$path   = $_SERVER['REQUEST_URI'];
if (isset($routes[$path])) {
    $c      = new $routes[$path]['controller']();
    $action = $routes[$path]['action'];
    $c->$action();
} else {
    http_response_code(400);
    echo "Not Found";
}
