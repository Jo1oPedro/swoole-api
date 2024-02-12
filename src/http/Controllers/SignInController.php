<?php

namespace App\http\Controllers;

use App\entitys\UserEntity;
use App\repositorys\UserMapper;
use Cascata\Framework\Http\Response;
use Firebase\JWT\JWT;
use Swoole\Http\Request;

class SignInController
{
    public function __construct(
        private UserMapper $mapper
    ) {}

    public function signIn(Request $request): Response
    {
        $user = UserEntity::create(...$request->post);
        try {
            $this->mapper->save($user);
        } catch (\PDOException $exception) {
            return Response::internalServerError($exception->getMessage());
        }

        $payload = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'exp' => time() + 60 * 60
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

        return Response::ok($jwt);
    }
}