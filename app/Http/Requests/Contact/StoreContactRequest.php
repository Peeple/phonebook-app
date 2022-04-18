<?php

namespace App\Http\Requests\Contact;

use App\Http\Requests\BaseRequest;
use App\Rules\Contact\ContactAssignedToUserRule;

class StoreContactRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'phone' => [
                'required',
                new ContactAssignedToUserRule()
            ]
        ];
    }
}
