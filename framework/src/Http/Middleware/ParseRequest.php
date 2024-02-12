<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Swoole\Http\Request;

class ParseRequest implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        if($request->getMethod() === 'POST' && $request->header['content-type'] === 'application/json') {
            $request->post = json_decode($request->rawContent(), true);
        }
        return $next->handle($request);
    }
}