<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use PDO;

class JWTHandler
{
    private string         $secret;
    private string         $algorithm;
    private int            $accessTtl;
    private int            $refreshTtl;
    private                $pdo;

    public function __construct(PDO $pdo)
    {
     $this->secret     = $_ENV['JWT_SECRET'] ?? 'change_this_secret';
     $this->algorithm  = $_ENV['JWT_ALGORITHM'] ?? 'HS256';
     $this->accessTtl  = (int)($_ENV['JWT_EXPIRATION'] ?? 3600);
     $this->refreshTtl = (int)($_ENV['JWT_REFRESH_EXPIRATION'] ?? 86400);

      $this->pdo = $pdo;
    }

    public function generateToken(array $user): string
{
    $payload = [
        'iss'   => 'agenda-semanal',
        'sub'   => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'iat'   => time(),
        'exp'   => time() + $this->accessTtl
    ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function generateRefreshToken(int $user_id): string
    {
        $refreshToken = bin2hex(random_bytes(64)); // Token longo e seguro

        $expiresAt = date('Y-m-d H:i:s', time() + $this->refreshTtl);

        $stmt = $this->pdo->prepare("
            INSERT INTO refresh_tokens (user_id, refresh_token, expires_at) 
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$user_id, $refreshToken, $expiresAt]);

        return $refreshToken;
    }

    public function decodeToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function findRefreshToken(string $token): ?array
{
    $stmt = $this->pdo->prepare(
        "SELECT rt.*, u.id AS user_id, u.name, u.email
         FROM refresh_tokens rt
         JOIN users u ON rt.user_id = u.id
         WHERE rt.refresh_token = :token
           AND rt.expires_at > NOW()
         LIMIT 1"
    );

    $stmt->execute([
        ':token' => $token
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
    }

    public function revokeRefreshToken(string $refreshToken): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM refresh_tokens WHERE refresh_token = ?");
        return $stmt->execute([$refreshToken]);
    }

}

