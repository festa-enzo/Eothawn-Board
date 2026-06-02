<?php
require_once '../config/database.php';
require_once '../app/Core/Response.php';
require_once '../app/Models/User.php';
require_once '../app/Controllers/AuthController.php';

// Rotas simples (pode melhorar depois)
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/api/login' && $method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $controller = new AuthController($pdo);
    $controller->login($data);
} 

else {
    Response::json(['error' => 'Rota não encontrada'], 404);
}