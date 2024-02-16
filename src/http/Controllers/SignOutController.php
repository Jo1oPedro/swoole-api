<?php

namespace App\http\Controllers;

use Cascata\Framework\Http\Response;
use Cascata\Framework\Http\Request;

class SignOutController
{
    public function __construct(
        private \Memcached $memcached
    ) {}

    public function signOut(Request $request)
    {
        $token = getAuthorizationToken($request);
        $bool = $this->memcached->set("{$token}", $token, time() + 60 * 60);
        return Response::ok(json_encode((int) $bool));
    }
}