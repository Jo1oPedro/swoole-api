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

    public function addGroup($prefix, callable $callback): self
    {
        parent::addGroup($prefix, $callback);
        return $this;
    }

    public function addMiddlewareGroup(array $middlewares, callable $callback): self
    {
        $this->middlewareGroup = $middlewares;
        $callback($this);
        return $this;
    }

    public function addRoute($httpMethod, $route, $handler)
    {
        if(is_array($handler)) {
            $handler = array_merge($handler, [$this->middlewareGroup]);
        }
        parent::addRoute($httpMethod, $route, $handler);
    }
}