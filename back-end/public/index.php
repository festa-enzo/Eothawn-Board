<?php

// Carrega as configurações e classes principais
require_once __DIR__ . '/../Config/Database.php';
require_once '../App/Core/Response.php';
require_once '../App/Core/Auth.php';

Response::cors();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);   // Finaliza a requisição preflight
}
// Carrega as rotas
$routes = require_once '../routes/api.php';

$pdo = Database::getConnection();

#$uri = str_replace('\\', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
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
    require_once "../App/Controllers/{$controllerName}.php";

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