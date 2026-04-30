<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Livre;
use App\Models\Progression;

class ProgressionController
{
    public function __construct(private Progression $progressionModel, private Livre $livreModel)
    {
    }

    public function save(): void
    {
        Auth::requireRole(['membre', 'moderateur', 'admin']);

        $livreId = (int) ($_POST['livre_id'] ?? 0);
        $pourcentage = (int) ($_POST['pourcentage'] ?? -1);
        $userId = (int) $_SESSION['user']['id'];
        $livre = $this->livreModel->findById($livreId);

        if (!$livre || $pourcentage < 0 || $pourcentage > 100) {
            Auth::forbidden();
        }

        $existing = $this->progressionModel->findByUserAndLivre($userId, $livreId);
        if ($existing) {
            $this->progressionModel->update((int) $existing['id'], $pourcentage);
        } else {
            $this->progressionModel->create($pourcentage, $userId, $livreId);
        }

        header('Location: index.php?action=livres');
        exit;
    }
}
