<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Swoole\Http\Request;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response;
}