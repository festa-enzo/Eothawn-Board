<?php

require_once '../Models/Task.php';
require_once '../Core/Response.php';

class TaskController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($data) {
        try {
            // Cria o objeto Usuario (faz todas as validações)
            $tarefa = new Tarefa($data['tarefa'], $data['horario'], $data['id_dia']);

            // Salva no banco
            $stmt = $this->pdo->prepare("INSERT INTO tarefas (tarefa, horario, id_dia) VALUES (?, ?, ?)");
            $stmt->execute([
                $tarefa->getNome(),
                $tarefa->getEmail(),
                $tarefa->getSenhaHash()
            ]);

            Response::json([
                'success' => true,
                'message' => 'Usuário cadastrado com sucesso!'
            ], 201);

        } catch (InvalidArgumentException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Erro interno no servidor'
            ], 500);
        }
    }

    public function login($data) {
        // Implementação simples (melhorar depois)
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($data['senha'], $user['senha'])) {
            $token = bin2hex(random_bytes(32));
            Response::json([
                'success' => true,
                'token' => $token,
                'user' => ['id' => $user['id'], 'nome' => $user['nome']]
            ]);
        } else {
            Response::json(['success' => false, 'message' => 'Credenciais inválidas'], 401);
        }
    }
}