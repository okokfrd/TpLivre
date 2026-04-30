<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Progress
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Récupère la progression d'un utilisateur pour plusieurs livres (sous forme de map)
     */
    public function getMapByUserAndBooks(int $userId, array $bookIds): array
    {
        if (empty($bookIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
        $sql = "SELECT book_id, pourcentage FROM progress WHERE user_id = ? AND book_id IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        $params = array_merge([$userId], $bookIds);
        $stmt->execute($params);

        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[(int) $row['book_id']] = (int) $row['pourcentage'];
        }
        return $map;
    }

    /**
     * Recherche la progression d'un utilisateur pour un livre
     */
    public function findByUserAndBook(int $userId, int $bookId): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM progress WHERE user_id = :user_id AND book_id = :book_id LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
        return $stmt->fetch();
    }

    /**
     * Crée une entrée de progression
     */
    public function create(int $pourcentage, int $userId, int $bookId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO progress (pourcentage, user_id, book_id) VALUES (:pourcentage, :user_id, :book_id)'
        );
        return $stmt->execute([
            'pourcentage' => $pourcentage,
            'user_id'     => $userId,
            'book_id'     => $bookId,
        ]);
    }

    /**
     * Met à jour la progression
     */
    public function update(int $id, int $pourcentage): bool
    {
        $stmt = $this->pdo->prepare('UPDATE progress SET pourcentage = :pourcentage WHERE id = :id');
        return $stmt->execute(['id' => $id, 'pourcentage' => $pourcentage]);
    }
}
