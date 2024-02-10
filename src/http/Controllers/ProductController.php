<?php

namespace App\http\Controllers;

use Cascata\Framework\Http\Response;

class ProductController
{
    public function index()
    {
        return Response::ok('sucesso');
    }

    public function show(int $id)
    {

    }
}