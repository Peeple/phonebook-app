<?php

namespace App\Http\Services\UserManager;

use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class SanctumUserManager implements UserManagerInterface
{

    public final function register(array $userArray): bool
    {
        $user = new User();
        if (empty($userArray['name']) || empty($userArray['email']) || empty($userArray['password'])) return false;
        $userArray['password'] = Hash::make($userArray['password']);
        $user->fill($userArray);
        return $user->save();
    }

    public final function login(array $userArray): string|bool
    {

        if (Auth::attempt($userArray)) {
            $user = auth()->user();
            $user->tokens()->delete();
            return $user->createToken('auth token')->plainTextToken;
        }

        return false;
    }

    public final function update(array $userArray, User $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user) return false;
        $user->fill($userArray);
        return $user->save();
    }

    public final function userDetails(array $user = null): ?User
    {
        return User::query()
            ->when(isset($user['id']) && !empty($user['id']), function ($query) use ($user) {
                return $query->whereId($user['id']);
            })
            ->when(isset($user['email']) && !empty($user['email']), function ($query) use ($user) {
                return $query->whereEmail($user['email']);
            })
            ->first() ?? auth()->user();
    }

    public final function resetPassword($userIdentifier = null): bool
    {
        $user = $userIdentifier ? User::firstWhere('email', $userIdentifier) : auth()->user();
        if (!$user) return false;
        return Password::sendResetLink($user->only('email')) === Password::RESET_LINK_SENT;
    }

    public final function updatePassword($confirmation, $credentials = null): bool
    {
        $user = User::firstWhere('email', $credentials['email']);
        $credentials = array_merge(['token' => $confirmation], $credentials, $user->only('email'));
        $resetStatus = Password::reset($credentials, function ($user, $password) {
            $user
                ->forceFill(['password' => Hash::make($password)]);
            $user->save();
        });


        return $resetStatus == Password::PASSWORD_RESET;
    }

    public final function logout(User $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user) return false;
        $user->tokens()->delete();
        return true;
    }
}
