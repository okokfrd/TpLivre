<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Review;
use App\Models\Book;

class ReviewController
{
    public function __construct(private Review $reviewModel, private Book $bookModel)
    {
    }

    /**
     * Affiche les avis d'un livre
     */
    public function showByBook(): void
    {
        Auth::requireLogin();

        $bookId = (int) ($_GET['book_id'] ?? 0);
        $livre  = $this->bookModel->findById($bookId);

        if (!$livre) {
            Auth::forbidden();
        }

        $userId = Auth::getUserId();
        $role   = Auth::getRole();

        // Admin et modérateur voient aussi les avis masqués
        $includeHidden = in_array($role, ['admin', 'moderateur'], true);
        $avis    = $this->reviewModel->getByBookId($bookId, $includeHidden);
        $monAvis = $this->reviewModel->findByUserAndBook($userId, $bookId);

        View::render('avis/index', [
            'livre'   => $livre,
            'avis'    => $avis,
            'monAvis' => $monAvis,
            'role'    => $role,
        ]);
    }

    /**
     * Sauvegarde un avis (création ou modification)
     */
    public function save(): void
    {
        Auth::requireLogin();

        $bookId      = (int) ($_POST['book_id'] ?? 0);
        $note        = (int) ($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');
        $userId      = Auth::getUserId();

        $livre = $this->bookModel->findById($bookId);
        if (!$livre) {
            Auth::forbidden();
        }

        if ($note < 1 || $note > 5) {
            $avis    = $this->reviewModel->getByBookId($bookId);
            $monAvis = $this->reviewModel->findByUserAndBook($userId, $bookId);
            View::render('avis/index', [
                'livre'   => $livre,
                'avis'    => $avis,
                'monAvis' => $monAvis,
                'role'    => Auth::getRole(),
                'error'   => 'La note doit être comprise entre 1 et 5.',
            ]);
            return;
        }

        $existing = $this->reviewModel->findByUserAndBook($userId, $bookId);

        if ($existing) {
            $this->reviewModel->update((int) $existing['id'], $note, $commentaire);
        } else {
            $this->reviewModel->create($note, $commentaire, $userId, $bookId);
        }

        header('Location: index.php?action=avis&book_id=' . $bookId);
        exit;
    }

    /**
     * Supprime son propre avis
     */
    public function delete(): void
    {
        Auth::requireLogin();

        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $bookId   = (int) ($_POST['book_id'] ?? 0);
        $userId   = Auth::getUserId();

        $this->reviewModel->delete($reviewId, $userId);

        header('Location: index.php?action=avis&book_id=' . $bookId);
        exit;
    }

    /**
     * Masque/affiche un avis (modération par admin/modérateur)
     */
    public function toggleHidden(): void
    {
        Auth::requireRole(['admin', 'moderateur']);

        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $bookId   = (int) ($_POST['book_id'] ?? 0);

        $this->reviewModel->toggleHidden($reviewId);

        header('Location: index.php?action=avis&book_id=' . $bookId);
        exit;
    }
}
