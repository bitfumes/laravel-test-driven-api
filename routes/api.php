<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list',TodoListController::class);

    Route::apiResource('todo-list.task',TaskController::class)
    ->except('show')
    ->shallow();
});

Route::post('/register',RegisterController::class)
->name('user.register');

Route::post('/login',LoginController::class)
->name('user.login');