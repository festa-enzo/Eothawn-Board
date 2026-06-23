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
    private                $pdo;

    public function __construct()
    {
        $this->secret     = $_ENV['JWT_SECRET']             ?? 'change_this_secret';
        $this->algorithm  = $_ENV['JWT_ALGORITHM'];
        $this->accessTtl  = (int)($_ENV['JWT_EXPIRATION']         ?? 3600);
        $this->refreshTtl = (int)($_ENV['JWT_REFRESH_EXPIRATION']  ?? 86400);

        $this->pdo = $pdo;
    }

    public static function generateToken($user){
        $payload = [
            'iss'   =>  'agenda-semanal',
            'sub'   =>  $user['id'],
            'nome'  =>  $user['nome'],
            'email' =>  $user['email'],
            'iat'   =>  time(),
            'exp'   =>  time()  +  $this->accessTtl
        ];

        return JWT::encode($payload, self::$secret, self::$algorithm);
    }

    public function generateRefreshToken(int $usuario_id): string
    {
        $refreshToken = bin2hex(random_bytes(64)); // Token longo e seguro

        $expiresAt = date('Y-m-d H:i:s', time() + $this->refreshTtl);

        $stmt = $this->pdo->prepare("
            INSERT INTO refresh_tokens (usuario_id, refresh_token, expires_at) 
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$usuario_id, $refreshToken, $expiresAt]);

        return $refreshToken;
    }

    public static function decodeToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, self::$algorithm));
            return (array) $decoded;
        } catch (Exception $e) {
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

    public function revokeRefreshToken(string $refreshToken): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM refresh_tokens WHERE refresh_token = ?");
        return $stmt->execute([$refreshToken]);
    }

}

