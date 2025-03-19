<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ModuleController;

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

//auth routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    //protected routes
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});


//Administrators group routes
Route::middleware(['auth:api', 'check.group:Administrators'])->group(function () {

    //user routes
    Route::get('users', [UserController::class, 'index']);
    Route::get('user/{username}', [UserController::class, 'show']);


    //group routes  
    Route::get('groups', [GroupController::class, 'index']);
    Route::get('group/{group}', [GroupController::class, 'show']);
    Route::post('group', [GroupController::class, 'store']);
    Route::put('group/{group}', [GroupController::class, 'update']);
    Route::delete('group/{group}', [GroupController::class, 'destroy']);
    Route::get('group/{group}/users', [GroupController::class, 'users']);

    //module routes
    Route::get('modules', [ModuleController::class, 'index']);
    Route::get('module/{module}', [ModuleController::class, 'show']);
    Route::post('module', [ModuleController::class, 'store']);
    Route::put('module/{module}', [ModuleController::class, 'update']);
    Route::delete('module/{module}', [ModuleController::class, 'destroy']);
    Route::get('module/{module}/users', [ModuleController::class, 'users']);
    Route::get('module/{module}/group', [ModuleController::class, 'group']);

});


