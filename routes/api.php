<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// Rotas para Usuários
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::post('/login', [UserController::class, 'login'])->name('login')->middleware('throttle:5,1');

// Rota para visualizar o usuário autenticado
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'show']);

// Rotas de produtos protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::patch('/products/{id}/status', [ProductController::class, 'updateStatus']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
});
