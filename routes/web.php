<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index']);

Route::prefix('tasks')->group(function () {
    Route::post('/store', [TaskController::class, 'store']);
    Route::post('/edit', [TaskController::class, 'update']);
    Route::post('/status/change', [TaskController::class, 'statusChange']);
    Route::get('/{task}', [TaskController::class, 'show']);
});
