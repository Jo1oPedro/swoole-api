<?php

namespace Cascata\Framework\Http\route;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

class RouterGrouper
{
    private static ?RouteCollector $routeCollector = null;

    private function __construct() {}

    public static function getInstance()
    {
        if(is_null(self::$routeCollector)) {
            self::$routeCollector = new RouteCollector(
                new Std(),
                new GroupCountBased()
            );
        }
        return self::$routeCollector;
    }
}