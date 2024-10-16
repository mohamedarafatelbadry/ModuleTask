<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\Api\UserController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('user', fn (Request $request) => $request->user())->name('user');
});

Route::middleware('auth:api')->post('users/restore/{id}', [UserController::class , 'restore']);

Route::middleware('auth:api')->delete('users/force-delete/{id}', [UserController::class , 'forceDelete']);

Route::middleware('auth:api')->apiResource('users', UserController::class);
