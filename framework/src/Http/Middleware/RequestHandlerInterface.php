<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Swoole\Http\Request;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
    public function injectMiddleware(array $middleware): void;
}