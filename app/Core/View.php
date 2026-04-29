<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $params = []): void
    {
        extract($params);
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}
