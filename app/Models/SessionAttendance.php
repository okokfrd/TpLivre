<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class SessionAttendance
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Vérifie si un utilisateur est inscrit à une session
     */
    public function isRegistered(int $sessionId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM session_attendance WHERE session_id = :session_id AND user_id = :user_id LIMIT 1'
        );
        $stmt->execute(['session_id' => $sessionId, 'user_id' => $userId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Inscrit un utilisateur à une session
     */
    public function register(int $sessionId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO session_attendance (session_id, user_id) VALUES (:session_id, :user_id)'
        );
        return $stmt->execute(['session_id' => $sessionId, 'user_id' => $userId]);
    }

    /**
     * Désinscrit un utilisateur d'une session
     */
    public function unregister(int $sessionId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM session_attendance WHERE session_id = :session_id AND user_id = :user_id'
        );
        return $stmt->execute(['session_id' => $sessionId, 'user_id' => $userId]);
    }

    /**
     * Compte le nombre d'inscrits à une session
     */
    public function countBySession(int $sessionId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM session_attendance WHERE session_id = :session_id');
        $stmt->execute(['session_id' => $sessionId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Liste des inscrits à une session
     */
    public function getParticipants(int $sessionId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.id, u.nom, u.email
             FROM session_attendance sa
             INNER JOIN users u ON u.id = sa.user_id
             WHERE sa.session_id = :session_id
             ORDER BY u.nom ASC'
        );
        $stmt->execute(['session_id' => $sessionId]);
        return $stmt->fetchAll();
    }
}
