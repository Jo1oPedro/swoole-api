<?php

namespace App\http\Middleware;

use Cascata\Framework\Http\Middleware\MiddlewareInterface;
use Cascata\Framework\Http\Middleware\RequestHandlerInterface;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

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
            $request->setAuthenticatedUserInfo(getAuthenticatedUserData($request));
        } catch (\Throwable $throwable) {
            return Response::unauthorized($throwable->getMessage());
        }

        return $next->handle($request);
    }
}