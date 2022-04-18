<?php

namespace App\Rules\Contact;

use App\Http\Services\PhoneUtil\PhoneUtilInterface;
use App\Http\Services\UserManager\UserManagerInterface;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ContactAssignedToUserRule implements Rule
{
    /**
     * @var User|null
     */
    private ?User $user;
    private PhoneUtilInterface $phoneUtil;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->user = app()->make(UserManagerInterface::class)->userDetails();
        $this->phoneUtil = app()->make(PhoneUtilInterface::class);
    }

    /**
     * Проверяем что контакт уже принадлежит пользователю
     * Check if contact already created for current user
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public final function passes($attribute, $value): bool
    {
        $userContacts = $this->user->contacts->pluck('phone')->toArray();
        return !in_array($this->phoneUtil->formatPhone($value), $userContacts);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public final function message(): string
    {
        return 'This number already in user\'s contacts';
    }
}
