<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'password' => 'sometimes|min:3|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'sometimes|min:3',
            'email' => 'sometimes|email:rfc|unique:users'
        ];
    }
}
