<?php

class Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByUser($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT t.*, c.titulo as column_titulo 
                                     FROM tasks t 
                                     JOIN columns c ON t.column_id = c.id 
                                     WHERE t.usuario_id = ? AND t.ativo = 1");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks 
            (usuario_id, column_id, titulo, descricao, cor, is_recorrente, recorrencia_tipo, dias_semana) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['usuario_id'],
            $data['column_id'],
            $data['titulo'],
            $data['descricao'] ?? null,
            $data['cor'] ?? '#3b82f6',
            $data['is_recorrente'] ?? 0,
            $data['recorrencia_tipo'] ?? null,
            $data['dias_semana'] ?? null
        ]);
    }

    public function update($id, $data) {
        // ... (implementar depois)
    }

    public function delete($id, $usuario_id) {
        // ... (implementar depois)
    }
}