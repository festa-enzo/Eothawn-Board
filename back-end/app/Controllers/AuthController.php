<?php

require_once '../Models/User.php';
require_once '../Core/Response.php';

class AuthController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($data) {
        try {
            // Cria o objeto Usuario (faz todas as validações)
            $usuario = new Usuario($data['nome'], $data['email'], $data['senha']);

            // Salva no banco
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([
                $usuario->getNome(),
                $usuario->getEmail(),
                $usuario->getSenhaHash()
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
}