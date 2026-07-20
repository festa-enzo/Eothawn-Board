<?php

declare(strict_types=1);

require_once __DIR__ . '/BaseRepository.php';

use PDO;

class TaskRepository extends BaseRepository
{
    protected string $table = 'tasks';

    protected array $sortableColumns = [
        'task_id',
        'title',
        'created_at'
    ];

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /**
     * Lista todas as tarefas do usuário
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                t.task_id,
                t.user_id,
                t.column_id,
                c.title AS column_title,
                t.title,
                t.is_recurring,
                t.week_days,
                t.time_task,
                t.active,
                t.created_at,
                t.updated_at
            FROM tasks t
            INNER JOIN columns c
                ON c.column_id = t.column_id
            WHERE
                t.user_id = :user_id
                AND t.active = 1
            ORDER BY
                c.column_id,
                t.time_task
        ");

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria uma nova tarefa
     */
public function createTask(array $data)
{
    $stmt = $this->db->prepare("
        SELECT column_id
        FROM columns
        WHERE user_id = ?
          AND position = ?
        LIMIT 1
    ");

    $stmt->execute([
        $data['user_id'],
        $data['column_id']
    ]);

    $column = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$column) {
        throw new Exception("Coluna não encontrada.");
    }

    $stmt = $this->db->prepare("
        INSERT INTO tasks
        (user_id, column_id, title, is_recurring, week_days, time_task, active)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['user_id'],
        $column['column_id'],
        $data['title'],
        $data['is_recurring'],
        $data['week_days'],
        $data['time_task'],
        $data['active']
    ]);

    $id = (int) $this->db->lastInsertId();

    return $this->findById($id);
}

    /**
     * Atualiza uma tarefa
     */
    public function updateTask(int $taskId, int $userId, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET
                column_id = :column_id,
                title = :title,
                is_recurring = :is_recurring,
                week_days = :week_days,
                time_task = :time_task,
                active = :active
            WHERE
                task_id = :task_id
                AND user_id = :user_id
        ");

        return $stmt->execute([
            ':task_id'       => $taskId,
            ':user_id'       => $userId,
            ':column_id'     => $data['column_id'],
            ':title'         => $data['title'],
            ':is_recurring'  => $data['is_recurring'] ?? 0,
            ':week_days'     => $data['week_days'] ?? null,
            ':time_task'     => $data['time_task'] ?? null,
            ':active'        => $data['active'] ?? 1
        ]);
    }

    /**
     * Remove uma tarefa
     */
    public function deleteTask(int $taskId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM tasks
            WHERE task_id = :task_id
            AND user_id = :user_id
        ");

        $stmt->execute([
            ':task_id' => $taskId,
            ':user_id' => $userId
        ]);

    return $stmt->rowCount() > 0;
}

    /**
     * Busca uma tarefa específica do usuário
     */
    public function findTaskById(int $taskId, int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM tasks
            WHERE
                task_id = :task_id
                AND user_id = :user_id
            LIMIT 1
        ");

        $stmt->execute([
            ':task_id' => $taskId,
            ':user_id' => $userId
        ]);

        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        return $task ?: null;
    }
    public function findById(int $taskId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM tasks
            WHERE task_id = :id
            LIMIT 1
        ");

    $stmt->execute([
        ':id' => $taskId
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
    public function moveTask(int $taskId, int $userId, int $position): bool
{
    // Descobre o column_id correspondente à posição
    $stmt = $this->db->prepare("
        SELECT column_id
        FROM columns
        WHERE user_id = :user_id
          AND position = :position
        LIMIT 1
    ");

    $stmt->execute([
        ":user_id" => $userId,
        ":position" => $position
    ]);

    $column = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$column) {
        throw new Exception("Coluna não encontrada.");
    }

    // Atualiza a tarefa
    $stmt = $this->db->prepare("
        UPDATE tasks
        SET column_id = :column_id
        WHERE task_id = :task_id
          AND user_id = :user_id
    ");

    return $stmt->execute([
        ":column_id" => $column["column_id"],
        ":task_id" => $taskId,
        ":user_id" => $userId
    ]);
}
}