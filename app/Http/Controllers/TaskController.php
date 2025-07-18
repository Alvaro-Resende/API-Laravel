<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Routing\Controller as BaseController;

class TaskController extends BaseController
{
    public function __construct()
    {
        // Garante que todas as rotas requerem autenticação via token JWT
        $this->middleware('auth:api');
    }

    // GET /api/tasks
    public function index()
    {
         $user = auth('api')->user();

         if (!$user) {
             \Log::warning('Usuario NAO autenticado ao acessar /api/tasks.');
             return response()->json(['error' => 'Usuario nao autenticado'], 401);
         }

         \Log::info('Usuario autenticado: ', ['id' => $user->id, 'email' => $user->email]);

         $tasks = Task::where('user_id', $user->id)->get();
         return response()->json($tasks);
    }

    // POST /api/tasks
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json($task, 201);
    }

    // GET /api/tasks/{id}
    public function show($id)
    {
        $user = auth('api')->user();
        $task = Task::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$task) {
            return response()->json(['error' => 'Tarefa nao encontrada'], 404);
        }

        return response()->json($task);
    }

    // PUT or PATCH /api/tasks/{id}
    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $task = Task::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$task) {
            return response()->json(['error' => 'Tarefa nao encontrada'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    // DELETE /api/tasks/{id}
    public function destroy($id)
    {
        $user = auth('api')->user();
        $task = Task::where('id', $id)
                    ->where('user_id', $user->id)
                    ->first();

        if (!$task) {
            return response()->json(['error' => 'Tarefa nao encontrada'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tarefa deletada com sucesso']);
    }
}
