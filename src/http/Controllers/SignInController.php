<?php

namespace App\http\Controllers;

use App\entitys\UserEntity;
use App\events\UserRegistered;
use App\http\requests\SignInRequest;
use App\Models\User;
use App\rabbitmq\RabbitmqManager;
use Cascata\Framework\Container\Container;
use Cascata\Framework\events\Events;
use Cascata\Framework\Http\Response;
use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager;

class SignInController
{
    public function signIn(SignInRequest $request): Response
    {
        /** @var Manager $container */
        //$container = Container::getInstance()->get('db');
        //$container->getConnection()->select('SELECT * FROM users');
        //$queryAll = Manager::select('SELECT * FROM users');

        $user = User::create($request->getValidatedFields());

        $payload = [
            'id' => $user->id,
            'email' => $user->email,
            'exp' => time() + 60 * 60
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

        Events::getInstance()->dispatch(
            UserRegistered::class,
            $user,
            "Bem vindo ao sistema"
        );

        /*RabbitmqManager::publishMessage(
            'user_registered',
            $user->getEmail()
        );*/

        return Response::ok($jwt);
    }
}