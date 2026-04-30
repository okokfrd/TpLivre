<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class User
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Recherche un utilisateur par email
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Recherche un utilisateur par ID
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create(string $nom, string $email, string $passwordHash, string $role = 'membre'): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nom, email, password_hash, role) VALUES (:nom, :email, :password_hash, :role)'
        );
        return $stmt->execute([
            'nom'           => $nom,
            'email'         => $email,
            'password_hash' => $passwordHash,
            'role'          => $role,
        ]);
    }

    /**
     * Récupère tous les utilisateurs (pour l'admin)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, nom, email, role, statut, created_at FROM users ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    /**
     * Met à jour le rôle d'un utilisateur
     */
    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute(['role' => $role, 'id' => $id]);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Compte le nombre total d'utilisateurs
     */
    public function countAll(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM users');
        return (int) $stmt->fetchColumn();
    }
}
