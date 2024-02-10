<?php

namespace Cascata\Framework\Container;

use DI\Container;
use Psr\Container\ContainerInterface;

class GlobalContainer
{
    private static ?ContainerInterface $container = null;

    private function __construct() {}

    public static function getInstance()
    {
        if(is_null(self::$container)) {
            self::$container = new Container();
        }
        return self::$container;
    }
}