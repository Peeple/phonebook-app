<?php

namespace App\Providers;

use App\Http\Services\PhoneUtil\LibPhoneNumberUtil;
use App\Http\Services\PhoneUtil\PhoneUtilInterface;
use Illuminate\Support\ServiceProvider;

class PhoneUtilProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PhoneUtilInterface::class, LibPhoneNumberUtil::class);
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
