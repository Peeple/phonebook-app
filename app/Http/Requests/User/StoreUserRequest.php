<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'password' => 'min:3|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:3',
            'email' => 'required|email:rfc|unique:users',
        ];
    }
}
