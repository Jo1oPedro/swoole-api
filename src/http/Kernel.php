<?php

namespace App\http;

use Cascata\Framework\Http\Middleware\AnswerRequest;
use Cascata\Framework\Http\Middleware\ExtractRouteInfo;
use Cascata\Framework\Http\Middleware\ParseRequest;

class Kernel
{
    private array $middleware = [
        ExtractRouteInfo::class,
        ParseRequest::class,
        AnswerRequest::class
    ];
}