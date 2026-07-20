<?php

require_once 'back-end/App/Repositories/TaskRepository.php';
require_once 'back-end/App/Core/Response.php';
require_once 'back-end/App/Core/Auth.php';

class TaskController {

    private PDO $pdo;
    private TaskRepository $taskRepository;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->taskRepository = new TaskRepository($pdo);
    }

    /**
     * GET /api/tasks - Listar tarefas do usuário
     */
    public function index() {
        try {
            $userId = Auth::requireAuth();   // ← Usa o Auth centralizado

            $tasks = $this->taskRepository->getByUser($userId);

            Response::json([
                'success' => true,
                'tasks'   => $tasks
            ]);
        } catch (Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Erro ao buscar tarefas'
            ], 500);
        }
    }

    /**
     * POST /api/tasks - Criar tarefa
     */
    public function create(array $data) {
        try {
            $userId = Auth::requireAuth();

        if (empty($data['title']) || empty($data['column_id'])) {
            Response::json([
                'success' => false,
                'message' => 'Título e coluna são obrigatórios'
            ], 400);
        }

        $taskData = [
            'user_id'      => $userId,
            'column_id'    => (int)$data['column_id'],
            'title'        => trim($data['title']),
            'is_recurring' => (int)($data['is_recurring'] ?? 0),
            'week_days'    => $data['week_days'] ?? null,
            'time_task'    => $data['time_task'] ?? null,
            'active'       => 1
        ];

        $taskId = $this->taskRepository->createTask($taskData);

        Response::json([
            'success' => true,
            'message' => 'Tarefa criada com sucesso!',
            'task_id' => $taskId
        ], 201);

        } catch (Exception $e) {
        // Isso ajuda muito a debugar
            error_log("Erro ao criar tarefa: " . $e->getMessage());
        
            Response::json([
                'success' => false,
                'message' => 'Erro interno ao salvar tarefa: ' . $e->getMessage()
            ], 500);
        }
}
    // Update e Delete (exemplo resumido)
    public function update(int $taskId, array $data) {
        try {
            $userId = Auth::requireAuth();
            $success = $this->taskRepository->updateTask($taskId, $userId, $data);

            Response::json([
                'success' => $success,
                'message' => $success ? 'Tarefa atualizada!' : 'Tarefa não encontrada'
            ]);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Erro ao atualizar'], 500);
        }
    }

    public function delete(int $taskId)
    {
        try {

            $userId = Auth::requireAuth();

            $success = $this->taskRepository->deleteTask($taskId, $userId);

        if (!$success) {
            Response::json([
                'success' => false,
                'message' => 'Tarefa não encontrada.'
            ], 404);
        }

        Response::json([
            'success' => true,
            'message' => 'Tarefa excluída com sucesso.'
        ]);

    } catch (Exception $e) {

        Response::json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}
    public function move($data)
    {
        try {

            $user = Auth::user();

            if (!$user) {

                Response::json([
                    "success" => false,
                    "message" => "Usuário não autenticado."
                ],401);

        }

        $this->taskRepository->moveTask(

            (int)$data["task_id"],
            (int)$user["sub"],
            (int)$data["column_id"]

        );

        Response::json([

            "success" => true

        ]);

    } catch(Exception $e){

        Response::json([

            "success" => false,
            "message" => $e->getMessage()

        ],500);

    }
}
}