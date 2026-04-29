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
}
