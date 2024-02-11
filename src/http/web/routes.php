<?php

use App\http\Controllers\ProductController;
use Cascata\Framework\Http\route\RouterGrouper;

$router = RouterGrouper::getInstance();

$router->addGroup('/products', function (FastRoute\RouteCollector $routes) {
    $routes->addRoute('GET', '', [ProductController::class, 'index']);
    $routes->addRoute('GET', '/{id:\d+}', [ProductController::class, 'show']);
});
$router->addRoute('GET', '/x', function (\Swoole\Http\Request $request) {
    return \Cascata\Framework\Http\Response::ok('oi mundo');
});
