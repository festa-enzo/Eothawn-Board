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

    public function __construct(PDO $db)
    {
        parent::__construct($db);
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
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks
            (
                user_id,
                column_id,
                title,
                is_recurring,
                week_days,
                time_task,
                active
            )
            VALUES
            (
                :user_id,
                :column_id,
                :title,
                :is_recurring,
                :week_days,
                :time_task,
                :active
            )
        ");

        $stmt->execute([
            ':user_id'       => $data['user_id'],
            ':column_id'     => $data['column_id'],
            ':title'         => $data['title'],
            ':is_recurring'  => $data['is_recurring'] ?? 0,
            ':week_days'     => $data['week_days'] ?? null,
            ':time_task'     => $data['time_task'] ?? null,
            ':active'        => $data['active'] ?? 1
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Atualiza uma tarefa
     */
    public function update(int $taskId, int $userId, array $data): bool
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
    public function delete(int $taskId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM tasks
            WHERE
                task_id = :task_id
                AND user_id = :user_id
        ");

        return $stmt->execute([
            ':task_id' => $taskId,
            ':user_id' => $userId
        ]);
    }

    /**
     * Busca uma tarefa específica do usuário
     */
    public function findById(int $taskId, int $userId): ?array
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
}