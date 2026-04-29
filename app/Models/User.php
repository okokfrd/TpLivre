<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class User
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        return $stmt->fetch();
    }

    public function create(string $nom, string $email, string $passwordHash, string $role = 'membre'): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nom, email, password_hash, role) VALUES (:nom, :email, :password_hash, :role)'
        );

        return $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => $role,
        ]);
    }
}
