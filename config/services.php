<?php

use App\Database\Connection;
use Cascata\Framework\Container\GlobalContainer;
use Cascata\Framework\Http\route\Route;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$container = GlobalContainer::getInstance();

$container->set('logger', function () {
    $logger = new Logger('app');
    $logger->pushHandler(new StreamHandler(BASE_PATH . "/" . $_ENV['LOG_STORAGE']));
    return $logger;
});

$container->set(Route::class, function () {
    return Route::getInstance();
});

$container->set(PDO::class, function () {
    return Connection::getInstance();
});

return $container;