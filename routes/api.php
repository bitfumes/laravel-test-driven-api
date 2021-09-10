<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

Route::apiResource('todo-list',TodoListController::class);

Route::get('task',[TaskController::class,'index'])->name('task.index');