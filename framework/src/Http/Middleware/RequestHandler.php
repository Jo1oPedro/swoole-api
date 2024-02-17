<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Container\Container;
use Cascata\Framework\Http\Response;
use Cascata\Framework\Http\Request;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [];

    public function __construct()
    {
        $kernelClass = new \ReflectionClass("App\\http\\Kernel");
        $this->middlewares = $kernelClass->getProperty("middleware")->getDefaultValue();
    }

    public function handle(Request $request): Response
    {
        if(empty($this->middlewares)) {
            Response::internalServerError('');
        }

        $middlewareClass = array_shift($this->middlewares);

        return Container::getInstance()
            ->get($middlewareClass)
            ->process($request, $this);
    }

    public function injectMiddleware(array $middlewares): void
    {
        array_unshift($this->middlewares, ...$middlewares);
    }
}