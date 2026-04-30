<?php

declare(strict_types=1);

namespace App\Core;

class Auth
{
    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Redirige vers le login si non connecté
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    /**
     * Vérifie si l'utilisateur a un rôle donné
     */
    public static function hasRole(string $role): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role;
    }

    /**
     * Vérifie que le rôle de l'utilisateur est dans la liste autorisée
     */
    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        $currentRole = $_SESSION['user']['role'] ?? '';
        if (!in_array($currentRole, $roles, true)) {
            self::forbidden();
        }
    }

    /**
     * Retourne le rôle courant de l'utilisateur
     */
    public static function getRole(): string
    {
        return $_SESSION['user']['role'] ?? '';
    }

    /**
     * Retourne l'ID de l'utilisateur connecté
     */
    public static function getUserId(): int
    {
        return (int) ($_SESSION['user']['id'] ?? 0);
    }

    /**
     * Affiche une page 403
     */
    public static function forbidden(): void
    {
        http_response_code(403);
        View::render('errors/403');
        exit;
    }
}
