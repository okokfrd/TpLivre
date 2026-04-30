<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Progression
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getMapByUserAndLivres(int $userId, array $livreIds): array
    {
        if (empty($livreIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($livreIds), '?'));
        $sql = "SELECT livre_id, pourcentage FROM progression WHERE user_id = ? AND livre_id IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        $params = array_merge([$userId], $livreIds);
        $stmt->execute($params);

        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['livre_id']] = (int) $row['pourcentage'];
        }

        return $map;
    }

    public function findByUserAndLivre(int $userId, int $livreId): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM progression WHERE user_id = :user_id AND livre_id = :livre_id LIMIT 1');
        $stmt->execute([
            'user_id' => $userId,
            'livre_id' => $livreId,
        ]);

        return $stmt->fetch();
    }

    public function create(int $pourcentage, int $userId, int $livreId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO progression (pourcentage, user_id, livre_id)
             VALUES (:pourcentage, :user_id, :livre_id)'
        );

        return $stmt->execute([
            'pourcentage' => $pourcentage,
            'user_id' => $userId,
            'livre_id' => $livreId,
        ]);
    }

    public function update(int $id, int $pourcentage): bool
    {
        $stmt = $this->pdo->prepare('UPDATE progression SET pourcentage = :pourcentage WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'pourcentage' => $pourcentage,
        ]);
    }
}
