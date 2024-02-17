<?php

use App\http\Controllers\ProductController;
use App\http\Controllers\SignInController;
use App\http\Controllers\SignOutController;
use App\http\Middleware\Authenticate;
use Cascata\Framework\Http\route\Route;

$router = Route::getInstance();

$router->addRoute('POST', '/signIn', [SignInController::class, 'signIn']);

$router->addGroup('/products', function (Route $route) {
    $route->addRoute('GET', '', [ProductController::class, 'index']);
    $route->addRoute('GET', '/{id:\d+}', [ProductController::class, 'show']);
    $route->addMiddlewareGroup([Authenticate::class], function (Route $routes) {
        $routes->addRoute('GET', '/x', [ProductController::class, 'index']);
    });
});

$router->addMiddlewareGroup([Authenticate::class], function (Route $route) {
    $route->addGroup('/cascata', function (Route $route) {
        $route->addRoute('GET', '/php', [ProductController::class, 'index']);
    });
    $route->addRoute('GET', '/cascata', [ProductController::class, 'index']);
    $route->addRoute('POST', '/logout', [SignOutController::class, 'signOut']);
});

$router->addRoute('GET', '/x', function (\Swoole\Http\Request $request, ProductController $productController) {
    return \Cascata\Framework\Http\Response::ok('oi mundo');
});
