<?php

namespace Cascata\Framework\Http\MIddleware;

use Cascata\Framework\Container\GlobalContainer;
use Cascata\Framework\Http\Response;
use DI\Container;
use Swoole\Http\Request;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        Authenticate::class,
        Success::class,
    ];

    public function handle(Request $request): Response
    {
        if(empty($this->middlewares)) {
            Response::internalServerError('');
        }

        /** @var MiddlewareInterface $middlewareClass */
        $middlewareClass = array_shift($this->middlewares);

        return GlobalContainer::getInstance()
            ->get($middlewareClass)
            ->process($request, $this);
    }
}