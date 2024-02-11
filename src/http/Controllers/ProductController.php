<?php

namespace App\http\Controllers;

use App\Database\Connection;
use Cascata\Framework\Http\Response;
use Firebase\JWT\JWT;

class ProductController
{
    public function index(): Response
    {
        return Response::ok('sucesso');
    }

    public function show(int $id): Response
    {
        $user = Connection::getInstance()
            ->prepare('Select * from users WHERE id = :id');
        $user->execute(['id' => $id]);
        $user = $user->fetchAll()[0];

        $payload = [
            'id' => $user['id'],
            'email' => $user['email'],
            'exp' => time() + 60 * 60
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

        return Response::ok(['token' => $jwt]);
    }
}