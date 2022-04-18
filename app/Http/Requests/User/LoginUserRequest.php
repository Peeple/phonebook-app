<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class LoginUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'sometimes|string',
            'password' => 'required|min:3|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:3',
        ];
    }
}
