<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Swoole\Http\Request;

class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private \Memcached $memcached
    ) {}

    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        if (empty($request->header['authorization'])) {
            return Response::unauthorized();
        }

        $token = getAuthorizationToken($request);

        if($this->memcached->get($token) === $token) {
            return Response::unauthorized('Token invalido');
        }

        try {
            getAuthenticatedUserData($request);
        } catch (\Throwable $throwable) {
            return Response::unauthorized($throwable->getMessage());
        }

        return $next->handle($request);
    }
}