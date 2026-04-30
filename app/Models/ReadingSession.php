<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class ReadingSession
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Crée une session
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO sessions (book_id, titre, date_heure, lieu, lien, description, created_by)
             VALUES (:book_id, :titre, :date_heure, :lieu, :lien, :description, :created_by)'
        );
        return $stmt->execute($data);
    }

    /**
     * Récupère toutes les sessions avec le titre du livre
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT s.*, b.titre AS livre_titre
             FROM sessions s
             INNER JOIN books b ON b.id = s.book_id
             ORDER BY s.date_heure DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Recherche une session par ID
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM sessions WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère les prochaines sessions d'un utilisateur (inscrit)
     */
    public function getUpcomingForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT s.*, b.titre AS livre_titre
             FROM sessions s
             INNER JOIN books b ON b.id = s.book_id
             INNER JOIN session_attendance sa ON sa.session_id = s.id
             WHERE sa.user_id = :user_id AND s.date_heure >= NOW()
             ORDER BY s.date_heure ASC
             LIMIT 5'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Supprime une session
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM sessions WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
