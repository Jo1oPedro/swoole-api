<?php

use App\http\Controllers\ProductController;
use Cascata\Framework\Http\Middleware\Authenticate;
use Cascata\Framework\Http\Middleware\Success;
use Cascata\Framework\Http\route\Route;

$router = Route::getInstance();

$router->addGroup('/products', function (FastRoute\RouteCollector $routes) {
    $routes->addRoute('GET', '', [ProductController::class, 'index']);
    $routes->addRoute('GET', '/{id:\d+}', [ProductController::class, 'show']);
});

$router->addMiddlewareGroup(function (Route $route) {
    $route->addRoute('GET', '/cascata', [ProductController::class, 'index']);
}, [Authenticate::class]);

$router->addRoute('GET', '/x', function (\Swoole\Http\Request $request, ProductController $productController) {
    return \Cascata\Framework\Http\Response::ok('oi mundo');
});
