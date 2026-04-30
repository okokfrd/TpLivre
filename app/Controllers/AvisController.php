<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Avis;
use App\Models\Livre;

class AvisController
{
    public function __construct(private Avis $avisModel, private Livre $livreModel)
    {
    }

    public function showByLivre(): void
    {
        Auth::requireRole(['membre', 'moderateur', 'admin']);

        $livreId = (int) ($_GET['livre_id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        $livre = $role === 'admin'
            ? $this->livreModel->findById($livreId)
            : $this->livreModel->findByIdAndUserId($livreId, $userId);

        if (!$livre) {
            Auth::forbidden();
        }

        $avis = $this->avisModel->getByLivreId($livreId);
        $monAvis = $this->avisModel->findByUserAndLivre($userId, $livreId);

        View::render('avis/index', [
            'livre' => $livre,
            'avis' => $avis,
            'monAvis' => $monAvis,
        ]);
    }

    public function save(): void
    {
        Auth::requireRole(['membre', 'moderateur', 'admin']);

        $livreId = (int) ($_POST['livre_id'] ?? 0);
        $note = (int) ($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        $livre = $role === 'admin'
            ? $this->livreModel->findById($livreId)
            : $this->livreModel->findByIdAndUserId($livreId, $userId);

        if (!$livre) {
            Auth::forbidden();
        }

        if ($note < 1 || $note > 5) {
            $avis = $this->avisModel->getByLivreId($livreId);
            $monAvis = $this->avisModel->findByUserAndLivre($userId, $livreId);
            View::render('avis/index', [
                'livre' => $livre,
                'avis' => $avis,
                'monAvis' => $monAvis,
                'error' => 'La note doit être entre 1 et 5.',
            ]);
            return;
        }

        $existing = $this->avisModel->findByUserAndLivre($userId, $livreId);

        if ($existing) {
            $this->avisModel->update((int) $existing['id'], $note, $commentaire);
        } else {
            $this->avisModel->create($note, $commentaire, $userId, $livreId);
        }

        header('Location: index.php?action=avis&livre_id=' . $livreId);
        exit;
    }
}
