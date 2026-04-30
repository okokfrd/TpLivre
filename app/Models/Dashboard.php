<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Dashboard
{
    public function __construct(private PDO $pdo)
    {
    }

    public function countLivresLus(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM progression WHERE user_id = :user_id AND pourcentage = 100');
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    public function countLivresEnCours(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM progression WHERE user_id = :user_id AND pourcentage > 0 AND pourcentage < 100');
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    public function moyenneNotes(int $userId): float
    {
        $stmt = $this->pdo->prepare('SELECT AVG(note) FROM avis WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchColumn();

        return $result !== null ? (float) $result : 0.0;
    }

    public function moyenneProgression(int $userId): float
    {
        $stmt = $this->pdo->prepare('SELECT AVG(pourcentage) FROM progression WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetchColumn();

        return $result !== null ? (float) $result : 0.0;
    }
}
