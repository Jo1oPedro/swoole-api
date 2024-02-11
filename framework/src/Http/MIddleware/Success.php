<?php

namespace Cascata\Framework\Http\MIddleware;

use Cascata\Framework\Http\Response;
use Cascata\Framework\Http\route\Router;
use Cascata\Framework\Http\route\RouterGrouper;
use Swoole\Http\Request;

class Success implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        return (new Router(RouterGrouper::getInstance()))($request);
    }
}