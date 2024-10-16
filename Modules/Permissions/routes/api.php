<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Permissions\App\Http\Controllers\Api\PermissionsController;
use Modules\Permissions\App\Http\Controllers\Api\RolesController;

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


Route::middleware('auth:api')->apiResource('roles', RolesController::class);

Route::middleware('auth:api')->get('permissions', [PermissionsController::class , 'index']);

