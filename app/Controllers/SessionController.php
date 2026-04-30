<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Livre;
use App\Models\ReadingSession;
use App\Models\SessionAttendance;

class SessionController
{
    public function __construct(
        private ReadingSession $sessionModel,
        private SessionAttendance $attendanceModel,
        private Livre $livreModel
    ) {
    }

    public function index(): void
    {
        Auth::requireLogin();

        $sessions = $this->sessionModel->getAll();
        $userId = (int) $_SESSION['user']['id'];

        foreach ($sessions as &$session) {
            $session['participants'] = $this->attendanceModel->countBySession((int) $session['id']);
            $session['registered'] = $this->attendanceModel->isRegistered((int) $session['id'], $userId);
        }

        View::render('sessions/index', ['sessions' => $sessions]);
    }

    public function createForm(): void
    {
        Auth::requireRole(['moderateur', 'admin']);

        $livres = $this->livreModel->getAll();

        View::render('sessions/create', ['livres' => $livres]);
    }

    public function store(): void
    {
        Auth::requireRole(['moderateur', 'admin']);

        $livreId = (int) ($_POST['livre_id'] ?? 0);
        $titre = trim($_POST['titre'] ?? '');
        $dateHeure = $_POST['date_heure'] ?? '';
        $lieu = trim($_POST['lieu'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($livreId <= 0 || $titre === '' || $dateHeure === '' || $lieu === '') {
            $this->createForm();
            return;
        }

        $this->sessionModel->create([
            'livre_id' => $livreId,
            'titre' => $titre,
            'date_heure' => $dateHeure,
            'lieu' => $lieu,
            'description' => $description,
            'created_by' => (int) $_SESSION['user']['id'],
        ]);

        header('Location: index.php?action=sessions');
        exit;
    }

    public function register(): void
    {
        Auth::requireLogin();

        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];

        if ($sessionId <= 0 || !$this->sessionModel->findById($sessionId)) {
            Auth::forbidden();
        }

        if (!$this->attendanceModel->isRegistered($sessionId, $userId)) {
            $this->attendanceModel->register($sessionId, $userId);
        }

        header('Location: index.php?action=sessions');
        exit;
    }
}
