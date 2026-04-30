<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Book;
use App\Models\ReadingSession;
use App\Models\SessionAttendance;

class SessionController
{
    public function __construct(
        private ReadingSession $sessionModel,
        private SessionAttendance $attendanceModel,
        private Book $bookModel
    ) {
    }

    /**
     * Liste toutes les sessions
     */
    public function index(): void
    {
        Auth::requireLogin();

        $sessions = $this->sessionModel->getAll();
        $userId   = Auth::getUserId();
        $role     = Auth::getRole();

        foreach ($sessions as &$session) {
            $session['participants'] = $this->attendanceModel->countBySession((int) $session['id']);
            $session['registered']  = $this->attendanceModel->isRegistered((int) $session['id'], $userId);
            // Liste des inscrits visible par admin/modérateur
            if (in_array($role, ['admin', 'moderateur'], true)) {
                $session['liste_inscrits'] = $this->attendanceModel->getParticipants((int) $session['id']);
            }
        }

        View::render('sessions/index', [
            'sessions' => $sessions,
            'role'     => $role,
        ]);
    }

    /**
     * Formulaire de création
     */
    public function createForm(): void
    {
        Auth::requireRole(['moderateur', 'admin']);

        $livres = $this->bookModel->getAll();
        View::render('sessions/create', ['livres' => $livres]);
    }

    /**
     * Traitement de la création
     */
    public function store(): void
    {
        Auth::requireRole(['moderateur', 'admin']);

        $bookId      = (int) ($_POST['book_id'] ?? 0);
        $titre       = trim($_POST['titre'] ?? '');
        $dateHeure   = $_POST['date_heure'] ?? '';
        $lieu        = trim($_POST['lieu'] ?? '');
        $lien        = trim($_POST['lien'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($bookId <= 0 || $titre === '' || $dateHeure === '') {
            $livres = $this->bookModel->getAll();
            View::render('sessions/create', [
                'livres' => $livres,
                'error'  => 'Le livre, le titre et la date sont obligatoires.',
            ]);
            return;
        }

        $this->sessionModel->create([
            'book_id'     => $bookId,
            'titre'       => $titre,
            'date_heure'  => $dateHeure,
            'lieu'        => $lieu,
            'lien'        => $lien,
            'description' => $description,
            'created_by'  => Auth::getUserId(),
        ]);

        header('Location: index.php?action=sessions');
        exit;
    }

    /**
     * Inscription à une session
     */
    public function register(): void
    {
        Auth::requireLogin();

        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $userId    = Auth::getUserId();

        if ($sessionId <= 0 || !$this->sessionModel->findById($sessionId)) {
            Auth::forbidden();
        }

        if (!$this->attendanceModel->isRegistered($sessionId, $userId)) {
            $this->attendanceModel->register($sessionId, $userId);
        }

        header('Location: index.php?action=sessions');
        exit;
    }

    /**
     * Désinscription d'une session
     */
    public function unregister(): void
    {
        Auth::requireLogin();

        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $userId    = Auth::getUserId();

        $this->attendanceModel->unregister($sessionId, $userId);

        header('Location: index.php?action=sessions');
        exit;
    }

    /**
     * Suppression d'une session (admin/modérateur)
     */
    public function delete(): void
    {
        Auth::requireRole(['admin', 'moderateur']);

        $id = (int) ($_POST['session_id'] ?? 0);
        $this->sessionModel->delete($id);

        header('Location: index.php?action=sessions');
        exit;
    }
}
