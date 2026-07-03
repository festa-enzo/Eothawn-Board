<?php
declare(strict_types=1);

require_once 'back-end/App/Repositories/BaseRepository.php';
require_once 'back-end/App/Repositories/AuthRepository.php';

use PDO;

class AuthModel {

    public function __construct($pdo){
        $this ->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE email = :email LIMIT 1"
        );
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }


}