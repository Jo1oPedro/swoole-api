<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
    public function injectMiddleware(array $middleware): void;
}