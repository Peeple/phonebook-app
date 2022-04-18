<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UserPasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'password' => 'required|min:3|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:3',
        ];
    }
}
