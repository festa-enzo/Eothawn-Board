<?php

// ====================== ROTAS DA API ======================

$routes = [
    // Auth
    'POST' => [
        '/api/register' => ['AuthController', 'register'],
        '/api/login'    => ['AuthController', 'login'],
        // '/api/logout'   => ['AuthController', 'logout'],
    ],

    'GET' => [
        '/api/tasks'    => ['TaskController', 'index'],
        '/api/columns'  => ['ColumnController', 'index'],
    ],

    'POST' => [
        '/api/tasks'           => ['TaskController', 'create'],
        '/api/tasks/move'      => ['TaskController', 'move'],      // mover tarefa entre colunas
    ],

    'PUT' => [
        '/api/tasks/{id}'      => ['TaskController', 'update'],
    ],

    'DELETE' => [
        '/api/tasks/{id}'      => ['TaskController', 'delete'],
    ]
];

// Não coloque código de execução aqui, só as rotas
return $routes;