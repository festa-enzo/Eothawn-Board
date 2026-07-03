<?php

class Auth {
    public static function verificarToken() {
        $headers = getallheaders();
        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

        if (!$token) {
            Response::json(['success' => false, 'message' => 'Token não fornecido'], 401);
        }

        // Por enquanto vamos usar token simples. Depois podemos melhorar com JWT
        // Aqui você pode validar o token no banco se quiser
        return $token; 
    }
}