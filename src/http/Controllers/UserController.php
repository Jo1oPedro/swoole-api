<?php

namespace App\http\Controllers;

use App\Models\User;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

class UserController
{
    public function index(Request $request)
    {
        $data = User::all()->map(function (User $user) {
            $userData = $user->toArray();
            unset($user['password']);
            unset($user['created_at']);
            unset($user['updated_at']);
            return $userData;
        });

        return Response::ok(['data' => $data]);
    }
}