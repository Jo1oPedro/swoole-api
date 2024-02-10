<?php

use App\http\Controllers\ProductController;
use Cascata\Framework\Http\route\RouterGrouper;

$router = RouterGrouper::getInstance();

$router->addRoute('GET', '/products', [ProductController::class, 'index']);
$router->addRoute('GET', '/products/{id:\d+}', [ProductController::class, 'show']);
$router->addRoute('GET', '/x', function (\Swoole\Http\Request $request) {
    return \Cascata\Framework\Http\Response::ok('oi mundo');
});
