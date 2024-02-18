<?php

namespace App\http\requests;

use Cascata\Framework\Http\requestValidation\FormRequest;

class SignInRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'email' => 'notBlank|email|stringType',
            'password' => 'notBlank|stringType',
            'name' => 'notBlank|stringType',
            //'numero' => 'between:10,20',
        ];
    }
}