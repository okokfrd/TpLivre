<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    /**
     * Charge et affiche une vue avec les données passées en paramètre
     */
    public static function render(string $view, array $params = []): void
    {
        // Extraction des variables pour les rendre disponibles dans la vue
        extract($params);

        // Inclure le fichier de vue
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}
