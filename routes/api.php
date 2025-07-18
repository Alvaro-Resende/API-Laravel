<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Login público
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas - precisa estar autenticado com token JWT
Route::middleware('auth:api')->group(function () {
    // Listar tasks do usuário logado
    Route::get('/tasks', [TaskController::class, 'index']);

    // Criar nova task
    Route::post('/tasks', [TaskController::class, 'store']);

    // Atualizar task pelo id
    Route::put('/tasks/{id}', [TaskController::class, 'update']);

    // Deletar task pelo id
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    // Mostrar Tarefa pelo ID
    Route::get('/tasks/{id}', [TaskController::class, 'show']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Teste para ver funcionamento do banco de dados
    Route::get('/debug/tarefas', function () {
        // Apenas como teste, retorna todas as tarefas
        return DB::table('tasks')->get();
    });
});
