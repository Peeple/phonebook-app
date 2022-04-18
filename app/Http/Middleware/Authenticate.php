<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * @OAS\SecurityScheme(
 *  securityScheme="bearerAuth",
 *  type="http",
 *  scheme="bearer",
 *  description="Login with email and password to get the authentication token",
 *  name="Token based authentication",
 *  in="header",
 * )
 **/
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        //Laravel 8 содержит редирект для обычных страниц,
        //но если в запросе не стоит Accept: application/json
        //проверка благополучно проваливалась
        if (!$request->expectsJson() && !(str_contains($request->path(), 'api/'))) {
            return route('login');
        }
    }
}
