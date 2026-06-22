<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use PDO;

class JWTHandler
{
    private string         $secret;
    private static         $algorithm;
    private int            $accessTtl;
    private int            $refreshTtl;

    public function __construct()
    {
        $this->secret     = $_ENV['JWT_SECRET']             ?? 'change_this_secret';
        $this->algorithm  = $_ENV['JWT_ALGORITHM'];
        $this->accessTtl  = (int)($_ENV['JWT_EXPIRATION']         ?? 3600);
        $this->refreshTtl = (int)($_ENV['JWT_REFRESH_EXPIRATION']  ?? 86400);
    }

    public static function generateToken($user){
        $payload = [
            'iss'   =>  'agenda-semanal',
            'sub'   =>  $user['id'],
            'nome'  =>  $user['nome'],
            'email' =>  $user['email'],
            'iat'   =>  time(),
            'exp'   =>  time()  +   (60 * 60)
        ];

        return JWT::encode($payload, self::$secret, self::$algorithm);
    }

    public static function decodeToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, self::$algorithm));
            return (array) $decoded;
        }   catch (Exception $e) {
            return null;
        }
    }
    
    public function findRefreshToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT rt.*, u.id AS user_id, u.email
             FROM refresh_tokens rt
             JOIN users u ON rt.user_id = u.id
             WHERE rt.token = :token AND rt.expires_at > NOW()
             LIMIT 1"
        );
    }
}

