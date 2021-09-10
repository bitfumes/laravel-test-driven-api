<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

Route::apiResource('todo-list',TodoListController::class);

Route::apiResource('todo-list.task',TaskController::class)
->except('show')
->shallow();