<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseRepository.php';
use PDO;

class TaskRepository extends BaseRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT t.*, c.title as column_titulo 
                                     FROM tasks t 
                                     JOIN columns c ON t.column_id = c.id 
                                     WHERE t.user_id = ? AND t.active = 1");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks 
            (user_id, column_id, title, time_, is_recurring, week_days, active, created_at, update_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['user_id'],
            $data['column_id'],
            $data['title'],
            $data['time_'] ?? null,            
            $data['is_recurring'] ?? 0,
            $data['active'] ?? null,
            $data['dias_semana'] ?? null,
            $data['created_at'],
            $data['update_at']
        ]);
    }

    public function update($id, $data) {
        // ... (implementar depois)
    }

    public function delete($id, $user_id) {
        // ... (implementar depois)
    }
}