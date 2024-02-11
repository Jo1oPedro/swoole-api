<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Swoole\Http\Request;

class Authenticate implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        /*if(empty($request->header['authorization'])) {
            return Response::unauthorized();
        }

        $token = str_replace('Bearer ', '', $request->header['authorization']);

        try {
            JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch (\Throwable $throwable) {
            return Response::unauthorized($throwable->getMessage());
        }*/

        return $next->handle($request);
    }
}