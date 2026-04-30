<?php

declare(strict_types=1);

namespace App\Core;

class Auth
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public static function hasRole(string $role): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role;
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        $currentRole = $_SESSION['user']['role'] ?? '';
        if (!in_array($currentRole, $roles, true)) {
            self::forbidden();
        }
    }

    public static function forbidden(): void
    {
        http_response_code(403);
        View::render('errors/403');
        exit;
    }
}
