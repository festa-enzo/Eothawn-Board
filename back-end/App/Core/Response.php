<?php

class Response {

    /**
     * Configuração de CORS
     */
    public static function cors() {
        // Para desenvolvimento - permite qualquer origem (menos seguro)
        header('Access-Control-Allow-Origin: http://agenda.eothawn.com');
        
        // Se quiser mais seguro (recomendado depois):
        // header('Access-Control-Allow-Origin: http://localhost');

        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, Usuario-Id, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');   // importante se usar cookies
        header('Access-Control-Max-Age: 3600');             // cache do preflight
    }

    public static function json($data, $status = 200) {
        self::cors();                    // ← Adiciona CORS em toda resposta
        http_response_code($status);
        header('Content-Type: application/json');
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
