<?php

namespace Cascata\Framework\Http\MIddleware;

use Cascata\Framework\Http\Response;
use Swoole\Http\Request;

class Authenticate implements MiddlewareInterface
{
    private bool $authenticated = true;

    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        if(!$this->authenticated) {
            return Response::unauthorized();
        }

        return $next->handle($request);
    }
}