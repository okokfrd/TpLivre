<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Livre
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM livres WHERE user_id = :user_id ORDER BY id DESC');
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function findByIdAndUserId(int $id, int $userId): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM livres WHERE id = :id AND user_id = :user_id LIMIT 1');
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);

        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO livres (titre, auteur, description, date_debut, date_fin, user_id)
             VALUES (:titre, :auteur, :description, :date_debut, :date_fin, :user_id)'
        );

        return $stmt->execute($data);
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE livres
             SET titre = :titre,
                 auteur = :auteur,
                 description = :description,
                 date_debut = :date_debut,
                 date_fin = :date_fin
             WHERE id = :id AND user_id = :user_id'
        );

        return $stmt->execute([
            'titre' => $data['titre'],
            'auteur' => $data['auteur'],
            'description' => $data['description'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id' => $id,
            'user_id' => $userId,
        ]);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM livres WHERE id = :id AND user_id = :user_id');

        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }
}
