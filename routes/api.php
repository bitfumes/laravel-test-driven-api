<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\WebServiceController;
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

    Route::get('/web-service/connect/{name}', [WebServiceController::class, 'connect'])->name('web-service.connect');
    Route::post('/web-service/callback', [WebServiceController::class, 'callback'])->name('web-service.callback');
    Route::post('/web-service/{web_service}', [WebServiceController::class, 'store'])->name('web-service.store');
});

Route::post('/register', RegisterController::class)
    ->name('user.register');

Route::post('/login', LoginController::class)
    ->name('user.login');
