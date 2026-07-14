<?php

require_once 'back-end/App/Repositories/TaskRepository.php';
require_once 'back-end/App/Core/Response.php';
require_once 'back-end/App/Core/Auth.php';

class TaskController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Criar uma nova tarefa
     */
    public function create(array $data): void
    {
        try {

            // Obtém o usuário autenticado pelo JWT
            $userId = Auth::id();

            // Validação dos campos obrigatórios
            if (empty($data['column_id']) || empty($data['title'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Título e coluna são obrigatórios.'
                ], 400);
            }

            $repository = new TaskRepository($this->pdo);

            $taskId = $repository->create([
                'user_id'       => $userId,
                'column_id'     => $data['column_id'],
                'title'         => $data['title'],
                'is_recurring'  => $data['is_recurring'] ?? 0,
                'week_days'     => $data['week_days'] ?? null,
                'time_task'     => $data['time_task'] ?? null,
                'active'        => 1
            ]);

            Response::json([
                'success' => true,
                'message' => 'Tarefa criada com sucesso.',
                'task_id' => $taskId
            ], 201);

        } catch (Throwable $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ], 500);

        }
    }

    /**
     * Lista todas as tarefas do usuário logado
     */
    public function list(): void
    {
        try {

            $userId = Auth::id();

            $repository = new TaskRepository($this->pdo);

            $tasks = $repository->getByUser($userId);

            Response::json([
                'success' => true,
                'tasks'   => $tasks
            ]);

        } catch (Throwable $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Atualizar tarefa
     */
    public function update(int $taskId, array $data): void
    {
        try {

            $userId = Auth::id();

            $repository = new TaskRepository($this->pdo);

            $repository->update($taskId, $userId, $data);

            Response::json([
                'success' => true,
                'message' => 'Tarefa atualizada com sucesso.'
            ]);

        } catch (Throwable $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Excluir tarefa
     */
    public function delete(array $data): void
    {
        try {

            if (empty($data['task_id'])) {
                Response::json([
                    'success' => false,
                    'message' => 'Task ID é obrigatório.'
                ], 400);
            }

            $userId = Auth::id();

            $repository = new TaskRepository($this->pdo);

            $repository->delete(
                (int)$data['task_id'],
                $userId
            );

            Response::json([
                'success' => true,
                'message' => 'Tarefa removida com sucesso.'
            ]);

        } catch (Throwable $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
}