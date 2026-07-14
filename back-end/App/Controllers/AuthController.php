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
        // Validação básica dos campos
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            Response::json([
                'success' => false, 
                'message' => 'Todos os campos são obrigatórios'
            ], 400);
        }

        $usuarioModel = new Usuario($this->pdo);

        // Verifica se o email já existe
        if ($usuarioModel->findByEmail($data['email'])) {
            Response::json([
                'success' => false, 
                'message' => 'Este email já está cadastrado'
            ], 409);
        }

        // Cria o objeto Usuario (faz as validações de nome, email e senha)
        $usuario = new Usuario(
            $data['nome'], 
            $data['email'], 
            $data['senha']
        );

        // Salva no banco
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, email, senha) 
            VALUES (?, ?, ?)
        ");

        $sucesso = $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getSenhaHash()
        ]);

        if ($sucesso) {
            Response::json([
                'success' => true,
                'message' => 'Usuário cadastrado com sucesso!'
            ], 201);
        } else {
            Response::json([
                'success' => false,
                'message' => 'Erro ao salvar usuário no banco'
            ], 500);
        }

    } catch (InvalidArgumentException $e) {
        // Erros de validação (nome, email, senha)
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
    $usuarioModel = new AuthRepository($this->pdo);
    $user = $usuarioModel->findByEmail($data['email']);

    if ($user && password_verify($data['password'], $user['password'])) {
        
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
            'user'        => ['id' => $user['id'], 'nome' => $user['name']]
        ]);

        return;
    }
    Response::json([
        'success' => false,
        'message' => 'E-mail ou senha inválidos.'
    ], 401);
    }
}