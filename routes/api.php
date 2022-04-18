<?php

use App\Http\Controllers\Api\Contact\ContactController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'name' => 'user',
    'prefix' => 'user',
    'as' => 'api.user.',
], function () {
    Route::post('', [UserController::class, 'create'])->name('create');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('forgot-password', [UserController::class, 'forgot'])->name('forgot');
});

Route::post('reset-password/{token}', [UserController::class, 'reset'])->name('password.reset');

Route::group([
    'name' => 'api',
    'as' => 'api.',
    'middleware' => ['auth:sanctum']
], function () {

    Route::group([
        'prefix' => 'user',
        'name' => 'user',
        'as' => 'user.'
    ], function () {
        Route::put('', [UserController::class, 'update'])->name('update');
        Route::get('', [UserController::class, 'details'])->name('details');
        Route::get('logout', [UserController::class, 'logout'])->name('logout');
    });

    Route::resource('contacts', ContactController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::put('contacts/{contact}/favourite', [ContactController::class, 'favourite'])->name('contacts.favourite');
});
