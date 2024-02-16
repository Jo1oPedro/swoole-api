<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response;
}