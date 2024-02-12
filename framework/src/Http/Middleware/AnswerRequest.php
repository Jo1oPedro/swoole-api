<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Cascata\Framework\Http\route\Router;
use Swoole\Http\Request;

class AnswerRequest implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        return (new Router())($request);
    }
}