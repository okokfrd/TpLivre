<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Document
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(string $nomFichier, string $chemin, int $taille, int $userId, int $livreId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO documents (nom_fichier, chemin, taille, user_id, livre_id)
             VALUES (:nom_fichier, :chemin, :taille, :user_id, :livre_id)'
        );

        return $stmt->execute([
            'nom_fichier' => $nomFichier,
            'chemin' => $chemin,
            'taille' => $taille,
            'user_id' => $userId,
            'livre_id' => $livreId,
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
