<?php

require_once __DIR__ . '/jwt.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/Response.php';

class Auth
{
    /**
     * Retorna o token enviado no header Authorization.
     */
    private static function getToken(): ?string
    {
        $headers = getallheaders();

        $authorization =
            $headers['Authorization']
            ?? $headers['authorization']
            ?? null;

        if (!$authorization) {
            return null;
        }

        if (!preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
            return null;
        }

        return $matches[1];
    }

    /**
     * Retorna os dados do usuário autenticado.
     */
    public static function user(): array
    {
        $token = self::getToken();

        if (!$token) {
            Response::json([
                'success' => false,
                'message' => 'Token não informado.'
            ], 401);
        }

        $jwt = new JWTHandler(Database::getConnection());

        $payload = $jwt->decodeToken($token);

        if (!$payload) {
            Response::json([
                'success' => false,
                'message' => 'Token inválido ou expirado.'
            ], 401);
        }

        return $payload;
    }

    /**
     * Retorna apenas o ID do usuário.
     */
    public static function id(): int
    {
        $user = self::user();

        return (int)$user['sub'];
    }

    /**
     * Verifica se o usuário está autenticado.
     */
    public static function check(): bool
    {
        return self::getToken() !== null;
    }
}