<?php

// Carrega as configurações e classes principais
require_once '../config/database.php';
require_once '../app/Core/Response.php';
require_once '../app/Core/Auth.php';

// Carrega as rotas
$routes = require_once '../routes/api.php';

$pdo = Database::getConnection();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string se existir (ex: ?id=1)
$uri = strtok($uri, '?');

// Verifica se a rota existe
if (isset($routes[$method][$uri])) {
    $controllerInfo = $routes[$method][$uri];
    $controllerName = $controllerInfo[0];
    $methodName     = $controllerInfo[1];

    // Inclui o controller dinamicamente
    require_once "../app/Controllers/{$controllerName}.php";

    $controller = new $controllerName($pdo);
    
    // Pega os dados da requisição
    $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

    // Chama o método do controller
    $controller->$methodName($data);

} else {
    Response::json([
        'success' => false,
        'message' => 'Rota não encontrada'
    ], 404);
}