<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Document
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(int $livreId, string $filename, string $filepath, int $uploadedBy): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO documents (livre_id, filename, filepath, uploaded_by, created_at)
             VALUES (:livre_id, :filename, :filepath, :uploaded_by, NOW())'
        );

        return $stmt->execute([
            'livre_id' => $livreId,
            'filename' => $filename,
            'filepath' => $filepath,
            'uploaded_by' => $uploadedBy,
        ]);
    }

    public function getByLivreId(int $livreId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM documents WHERE livre_id = :livre_id ORDER BY id DESC');
        $stmt->execute(['livre_id' => $livreId]);

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM documents WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }
}
