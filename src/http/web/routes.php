<?php

use App\http\Controllers\ProductController;
use Cascata\Framework\Http\route\RouterGrouper;

$router = RouterGrouper::getInstance();

$router->addRoute('GET', '/products', [ProductController::class, 'index']);
$router->addRoute('GET', '/product/{id:\d+}', [ProductController::class, 'show']);