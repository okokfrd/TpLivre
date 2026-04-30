<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Book;
use App\Models\Progress;

class ProgressController
{
    public function __construct(private Progress $progressModel, private Book $bookModel)
    {
    }

    /**
     * Sauvegarde la progression de lecture
     */
    public function save(): void
    {
        Auth::requireLogin();

        $bookId      = (int) ($_POST['book_id'] ?? 0);
        $pourcentage = (int) ($_POST['pourcentage'] ?? -1);
        $userId      = Auth::getUserId();

        $livre = $this->bookModel->findById($bookId);

        // Validation : le livre doit exister et le pourcentage être entre 0 et 100
        if (!$livre || $pourcentage < 0 || $pourcentage > 100) {
            Auth::forbidden();
        }

        $existing = $this->progressModel->findByUserAndBook($userId, $bookId);

        if ($existing) {
            $this->progressModel->update((int) $existing['id'], $pourcentage);
        } else {
            $this->progressModel->create($pourcentage, $userId, $bookId);
        }

        header('Location: index.php?action=livres');
        exit;
    }
}
