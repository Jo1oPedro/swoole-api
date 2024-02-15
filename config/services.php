<?php

use App\Database\Connection;
use Cascata\Framework\Container\GlobalContainer;
use Cascata\Framework\Http\route\Route;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$container = GlobalContainer::getInstance();

## LOGGER
$container->set('logger', function () {
    $logger = new Logger('app');
    $logger->pushHandler(new StreamHandler(BASE_PATH . "/" . $_ENV['LOG_STORAGE']));
    return $logger;
});

## ROUTE
$container->set(Route::class, function () {
    return Route::getInstance();
});


## CONEXAO MYSQL
$container->set(PDO::class, function () {
    return Connection::getInstance();
});

## CACHE
$memcached = new Memcached();
$memcached->addServer('banco_de_dados_em_memoria', 11211);
$container->set(Memcached::class, $memcached);

return $container;