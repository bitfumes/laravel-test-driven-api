<?php

use Illuminate\Support\Facades\Route;

Route::get('list',[TodoListController::class,'index'])->name('todo-list.store');