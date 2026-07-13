<?php

class Column {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByUser($usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM columns WHERE usuario_id = ? ORDER BY ordem");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($usuario_id, $titulo, $ordem) {
        $stmt = $this->pdo->prepare("INSERT INTO columns (usuario_id, titulo, ordem) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $titulo, $ordem]);
        return $this->pdo->lastInsertId();
    }
}

//ALTERAR AS COLUNAS E TABELAS DE ACORDO COM O BANCO PRÓPRIO