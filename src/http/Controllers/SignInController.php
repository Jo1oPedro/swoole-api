<?php

namespace App\http\Controllers;

use App\entitys\UserEntity;
use App\http\requests\SignInRequest;
use App\rabbitmq\RabbitmqManager;
use App\repositorys\UserMapper;
use Cascata\Framework\events\Events;
use Cascata\Framework\Http\Response;
use Firebase\JWT\JWT;

class SignInController
{
    public function __construct(
        private UserMapper $mapper
    ) {}

    public function signIn(SignInRequest $request): Response
    {
        $user = UserEntity::create(...$request->getValidatedFields());
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

        Events::getInstance()->dispatch('user-registered-event', $user->getEmail());

        /*RabbitmqManager::publishMessage(
            'user_registered',
            $user->getEmail()
        );*/

        return Response::ok($jwt);
    }
}