<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Avis
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getByLivreId(int $livreId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.id, a.note, a.commentaire, a.user_id, a.livre_id, u.nom
             FROM avis a
             INNER JOIN users u ON u.id = a.user_id
             WHERE a.livre_id = :livre_id
             ORDER BY a.id DESC'
        );
        $stmt->execute(['livre_id' => $livreId]);

        return $stmt->fetchAll();
    }

    public function findByUserAndLivre(int $userId, int $livreId): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM avis WHERE user_id = :user_id AND livre_id = :livre_id LIMIT 1');
        $stmt->execute([
            'user_id' => $userId,
            'livre_id' => $livreId,
        ]);

        return $stmt->fetch();
    }

    public function create(int $note, string $commentaire, int $userId, int $livreId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO avis (note, commentaire, user_id, livre_id)
             VALUES (:note, :commentaire, :user_id, :livre_id)'
        );

        return $stmt->execute([
            'note' => $note,
            'commentaire' => $commentaire,
            'user_id' => $userId,
            'livre_id' => $livreId,
        ]);
    }

    public function update(int $id, int $note, string $commentaire): bool
    {
        $stmt = $this->pdo->prepare('UPDATE avis SET note = :note, commentaire = :commentaire WHERE id = :id');

        return $stmt->execute([
            'id' => $id,
            'note' => $note,
            'commentaire' => $commentaire,
        ]);
    }
}
