<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class SessionAttendance
{
    public function __construct(private PDO $pdo)
    {
    }

    public function isRegistered(int $sessionId, int $userId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM session_attendance WHERE session_id = :session_id AND user_id = :user_id LIMIT 1');
        $stmt->execute([
            'session_id' => $sessionId,
            'user_id' => $userId,
        ]);

        return (bool) $stmt->fetch();
    }

    public function register(int $sessionId, int $userId): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO session_attendance (session_id, user_id) VALUES (:session_id, :user_id)');
        return $stmt->execute([
            'session_id' => $sessionId,
            'user_id' => $userId,
        ]);
    }

    public function countBySession(int $sessionId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM session_attendance WHERE session_id = :session_id');
        $stmt->execute(['session_id' => $sessionId]);
        return (int) $stmt->fetchColumn();
    }
}
