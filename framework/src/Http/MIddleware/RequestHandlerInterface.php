<?php

namespace Cascata\Framework\Http\MIddleware;

use Cascata\Framework\Http\Response;
use Swoole\Http\Request;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}