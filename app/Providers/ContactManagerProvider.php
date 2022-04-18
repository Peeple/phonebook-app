<?php

namespace App\Providers;

use App\Http\Services\ContactManager\ContactManagerInterface;
use App\Http\Services\ContactManager\EloquentContactManager;
use App\Http\Services\UserManager\SanctumUserManager;
use App\Http\Services\UserManager\UserManagerInterface;
use Illuminate\Support\ServiceProvider;

class ContactManagerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContactManagerInterface::class, EloquentContactManager::class);
        $this->app->when(EloquentContactManager::class)
            ->needs(UserManagerInterface::class)
            ->give(SanctumUserManager::class);
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
