<?php

namespace App\Http\Services\UserManager;

use App\Models\User;

interface UserManagerInterface
{
    public function register(array $userArray);

    public function login(array $userArray);

    public function update(array $userArray);

    public function userDetails(): ?User;

    public function resetPassword($userIdentifier = null):bool;

    public function updatePassword($confirmation, $credentials = null):bool;

    public function logout();
}
