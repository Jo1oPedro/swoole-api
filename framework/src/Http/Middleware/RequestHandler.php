<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Container\GlobalContainer;
use Cascata\Framework\Http\Response;
use DI\Container;
use Swoole\Http\Request;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        ParseRequest::class,
        ExtractRouteInfo::class,
        AnswerRequest::class
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

    public function injectMiddleware(array $middlewares): void
    {
        array_unshift($this->middlewares, ...$middlewares);
    }
}