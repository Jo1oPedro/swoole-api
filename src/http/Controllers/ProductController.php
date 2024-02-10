<?php

namespace App\http\Controllers;

use Cascata\Framework\Http\Response;

class ProductController
{
    public function index()
    {
        return Response::ok('sucesso');
        //return [200, ['Content-Type' => 'application/json'], json_encode('sucesso')];
    }
}