<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Document
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Crée un document lié à un livre
     */
    public function create(int $bookId, string $filename, string $filepath, string $mime, int $size, int $uploadedBy): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO documents (book_id, filename, filepath, mime, size, uploaded_by)
             VALUES (:book_id, :filename, :filepath, :mime, :size, :uploaded_by)'
        );
        return $stmt->execute([
            'book_id'     => $bookId,
            'filename'    => $filename,
            'filepath'    => $filepath,
            'mime'        => $mime,
            'size'        => $size,
            'uploaded_by' => $uploadedBy,
        ]);
    }

    /**
     * Récupère les documents d'un livre
     */
    public function getByBookId(int $bookId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT d.*, u.nom AS uploaded_by_nom
             FROM documents d
             LEFT JOIN users u ON u.id = d.uploaded_by
             WHERE d.book_id = :book_id
             ORDER BY d.created_at DESC'
        );
        $stmt->execute(['book_id' => $bookId]);
        return $stmt->fetchAll();
    }

    /**
     * Recherche un document par ID
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM documents WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Supprime un document
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM documents WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
