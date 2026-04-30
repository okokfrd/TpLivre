<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Dashboard
{
    public function __construct(private PDO $pdo)
    {
    }

    // ===================== STATS PERSONNELLES =====================

    /**
     * Nombre de livres lus à 100% par l'utilisateur
     */
    public function countLivresLus(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM progress WHERE user_id = :user_id AND pourcentage = 100');
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Nombre de livres en cours (progression entre 1 et 99)
     */
    public function countLivresEnCours(int $userId): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM progress WHERE user_id = :user_id AND pourcentage > 0 AND pourcentage < 100'
        );
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Moyenne des notes données par l'utilisateur
     */
    public function moyenneNotes(int $userId): float
    {
        $stmt = $this->pdo->prepare('SELECT AVG(note) FROM reviews WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchColumn();
        return $result !== null && $result !== false ? round((float) $result, 1) : 0.0;
    }

    /**
     * Progression moyenne de l'utilisateur
     */
    public function moyenneProgression(int $userId): float
    {
        $stmt = $this->pdo->prepare('SELECT AVG(pourcentage) FROM progress WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchColumn();
        return $result !== null && $result !== false ? round((float) $result, 1) : 0.0;
    }

    /**
     * Livres en cours avec progression de l'utilisateur
     */
    public function getLivresEnCours(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT b.id, b.titre, b.auteur, b.cover_path, p.pourcentage
             FROM progress p
             INNER JOIN books b ON b.id = p.book_id
             WHERE p.user_id = :user_id AND p.pourcentage > 0 AND p.pourcentage < 100
             ORDER BY p.updated_at DESC
             LIMIT 5'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    // ===================== STATS GLOBALES (admin/modérateur) =====================

    /**
     * Nombre total de membres
     */
    public function countMembres(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM users');
        return (int) $stmt->fetchColumn();
    }

    /**
     * Nombre total de livres
     */
    public function countLivres(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM books');
        return (int) $stmt->fetchColumn();
    }

    /**
     * Nombre total d'avis
     */
    public function countAvis(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM reviews');
        return (int) $stmt->fetchColumn();
    }

    /**
     * Note moyenne globale
     */
    public function moyenneNotesGlobale(): float
    {
        $stmt = $this->pdo->query('SELECT AVG(note) FROM reviews WHERE is_hidden = 0');
        $result = $stmt->fetchColumn();
        return $result !== null && $result !== false ? round((float) $result, 1) : 0.0;
    }

    /**
     * Progression moyenne globale
     */
    public function moyenneProgressionGlobale(): float
    {
        $stmt = $this->pdo->query('SELECT AVG(pourcentage) FROM progress');
        $result = $stmt->fetchColumn();
        return $result !== null && $result !== false ? round((float) $result, 1) : 0.0;
    }
}
