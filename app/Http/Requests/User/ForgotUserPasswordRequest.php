<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class ForgotUserPasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email:rfc',
            'password' => 'required|min:3|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:3',
        ];
    }
}
