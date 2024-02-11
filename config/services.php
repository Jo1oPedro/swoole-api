<?php

use Cascata\Framework\Container\GlobalContainer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$container = GlobalContainer::getInstance();

$container->set('logger', function () {
    $logger = new Logger('app');
    $logger->pushHandler(new StreamHandler(BASE_PATH . "/" . $_ENV['LOG_STORAGE']));
    return $logger;
});

return $container;