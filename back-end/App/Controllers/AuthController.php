<?php

require_once 'back-end/App/Models/User.php';
require_once 'back-end/App/Repositories/AuthRepository.php';
require_once 'back-end/App/Core/Response.php';

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

    public function login($data) {
    $usuarioModel = new AuthModel($this->pdo);
    $user = $usuarioModel->findByEmail($data['email']);

    if ($user && password_verify($data['senha'], $user['senha'])) {
        
        $jwtHandler = new JWTHandler($this->pdo);

        $accessToken  = $jwtHandler->generateAccessToken($user);
        $refreshToken = $jwtHandler->generateRefreshToken($user['id']);

        // Envia Refresh Token como Cookie seguro
        setcookie('refresh_token', $refreshToken, [
            'expires'  => time() + $jwtHandler->refreshTtl, // ou usar $this->refreshTtl
            'path'     => '/',
            'httponly' => true,
            'secure'   => false,     // mude para true em produção (HTTPS)
            'samesite' => 'Strict'
        ]);

        Response::json([
            'success'     => true,
            'accessToken' => $accessToken,
            'user'        => ['id' => $user['id'], 'nome' => $user['nome']]
        ]);
    }
    }
}