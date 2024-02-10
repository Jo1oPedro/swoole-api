<?php

namespace App\http\Controllers;

use App\Database\Connection;
use Cascata\Framework\Http\Response;

class ProductController
{
    public function index(): Response
    {
        return Response::ok('sucesso');
    }

    public function show(int $id): Response
    {
        $user = Connection::getInstance()
            ->query('Select * from users')
            ->fetchAll();

        return Response::ok($user);
    }
}