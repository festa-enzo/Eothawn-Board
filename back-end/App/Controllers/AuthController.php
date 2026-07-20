<?php

require_once 'back-end/App/Models/User.php';
require_once 'back-end/App/Repositories/AuthRepository.php';
require_once 'back-end/App/Core/Response.php';
require_once 'back-end/App/Core/jwt.php'; // ajuste o caminho se necessário

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

        $usuarioModel = new AuthRepository($this->pdo);

        // Verifica se o email já existe
        if ($usuarioModel->findByEmail($data['email'])) {
            Response::json([
                'success' => false, 
                'message' => 'Este email já está cadastrado'
            ], 409);
        }

        // Cria o objeto Usuario (faz as validações de nome, email e senha)
        $usuario = Usuario::createFromRaw(
            $data['nome'],
            $data['email'],
            $data['senha']
        );

        // Salva no banco
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $sucesso = $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getSenhaHash()
        ]);
        
        $userId = (int)$this->pdo->lastInsertId();
        $columns = [
        ['Segunda', 1],
        ['Terça', 2],       
        ['Quarta', 3],
        ['Quinta', 4],
        ['Sexta', 5],
        ['Sábado', 6],
        ['Domingo', 7],
        ];

        $stmtColumn = $this->pdo->prepare("
            INSERT INTO columns (user_id, title, position)
            VALUES (?, ?, ?)
        ");

foreach ($columns as $column) {

    $stmtColumn->execute([
        $userId,
        $column[0],
        $column[1]
    ]);

};
        

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
        try {
            if (empty($data['email']) || empty($data['senha'])) {
                Response::json([
                    'success' => false,
                    'message' => 'E-mail e senha são obrigatórios'
                ], 400);
            }

            $usuarioModel = new AuthRepository($this->pdo);
            $user = $usuarioModel->findByEmail($data['email']);
            
            if (!$user || !password_verify($data['senha'], $user['password'])) {
                Response::json([
                    'success' => false,
                    'message' => 'E-mail ou senha inválidos.'
                ], 401);
                return;
            }

            $jwtHandler = new JWTHandler($this->pdo);

            $accessToken  = $jwtHandler->generateToken($user);

            $refreshToken = $jwtHandler->generateRefreshToken($user['id']);

            // Cookie Refresh Token
            setcookie('refresh_token', $refreshToken, [
                'expires'  => time() + 604800, // 7 dias
                'path'     => '/',
                'httponly' => true,
                'secure'   => false,
                'samesite' => 'Strict'
            ]);

            Response::json([
                'success'     => true,
                'accessToken' => $accessToken,
                'user'        => [
                    'id'   => $user['id'],
                    'name' => $user['name']
                ]
            ]);

        } catch (Exception $e) {
            Response::json([
                'success' => false,
                'message' => 'Erro interno no servidor'
            ], 500);
        }
    }
}