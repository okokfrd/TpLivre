<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class ReadingSession
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO sessions (livre_id, titre, date_heure, lieu, description, created_by)
             VALUES (:livre_id, :titre, :date_heure, :lieu, :description, :created_by)'
        );

        return $stmt->execute($data);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.*, l.titre AS livre_titre
             FROM sessions s
             INNER JOIN livres l ON l.id = s.livre_id
             ORDER BY s.date_heure ASC'
        );

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sessions WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }
}
