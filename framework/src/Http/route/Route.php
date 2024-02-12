<?php

namespace Cascata\Framework\Http\route;

use Cascata\Framework\Http\Middleware\MiddlewareInterface;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

class Route extends RouteCollector
{
    private static Route|null $instance = null;

    private array $middlewareGroup = [];

    private function __construct()
    {
        parent::__construct(
            new Std(),
            new GroupCountBased()
        );
    }

    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addMiddlewareGroup(callable $callback, array $middlewares): void
    {
        $this->middlewareGroup = $middlewares;
        $callback($this);
    }

    public function addRoute($httpMethod, $route, $handler)
    {
        if(is_array($handler)) {
            $handler = array_merge($handler, [$this->middlewareGroup]);
        }
        parent::addRoute($httpMethod, $route, $handler);
    }
}