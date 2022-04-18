<?php

namespace App\Providers;

use App\Http\Services\UserManager\SanctumUserManager;
use App\Http\Services\UserManager\UserManagerInterface;
use Illuminate\Support\ServiceProvider;

class UserManagerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserManagerInterface::class, SanctumUserManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
