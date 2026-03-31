<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks/report', 'report');
    Route::get('/tasks', 'index');
    Route::post('/tasks', 'store');
    Route::get('/tasks/{task}', 'show');
    Route::patch('/tasks/{task}/status', 'update');
    Route::delete('/tasks/{task}', 'destroy');
});
