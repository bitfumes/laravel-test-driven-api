<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list', TodoListController::class);

    Route::apiResource('todo-list.task', TaskController::class)
        ->except('show')
        ->shallow();

    Route::apiResource('label', LabelController::class);

    Route::get('/service/connect/{service}', [ServiceController::class, 'connect'])->name('service.connect');
});

Route::post('/register', RegisterController::class)
    ->name('user.register');

Route::post('/login', LoginController::class)
    ->name('user.login');
