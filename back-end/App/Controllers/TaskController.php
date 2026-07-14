<?php

require_once __DIR__ . '/../Repositories/TaskRepository.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Core/Auth.php';

class TaskController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * GET /api/tasks
     */
    public function index()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                Response::json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }

            $tasks = $this->taskRepository->getByUser($user['id']);

            Response::json([
                'success' => true,
                'tasks' => $tasks
            ]);

        } catch (Exception $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * POST /api/tasks
     */
    public function create($data)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                Response::json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }

            if (empty($data['title']) || empty($data['column_id']) || empty($data['time_task'])) {

                Response::json([
                    'success' => false,
                    'message' => 'Título, coluna e horário são obrigatórios.'
                ], 400);

            }

            $task = $this->taskRepository->create([

                'user_id'       => $user['id'],
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
                'task' => $task
            ], 201);

        } catch (Exception $e) {

            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * PUT /api/tasks/{id}
     */
    public function update($id, $data)
    {
        Response::json([
            'success' => false,
            'message' => 'Ainda não implementado.'
        ], 501);
    }

    /**
     * DELETE /api/tasks/{id}
     */
    public function delete($id)
    {
        Response::json([
            'success' => false,
            'message' => 'Ainda não implementado.'
        ], 501);
    }

    /**
     * POST /api/tasks/move
     */
    public function move($data)
    {
        Response::json([
            'success' => false,
            'message' => 'Ainda não implementado.'
        ], 501);
    }
}