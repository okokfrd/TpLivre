<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Dashboard;

class HomeController
{
    public function __construct(private Dashboard $dashboardModel)
    {
    }

    public function dashboard(): void
    {
        Auth::requireLogin();

        $userId = (int) $_SESSION['user']['id'];
        $stats = [
            'livres_lus' => $this->dashboardModel->countLivresLus($userId),
            'livres_en_cours' => $this->dashboardModel->countLivresEnCours($userId),
            'moyenne_notes' => $this->dashboardModel->moyenneNotes($userId),
            'moyenne_progression' => $this->dashboardModel->moyenneProgression($userId),
        ];

        View::render('home/dashboard', ['user' => $_SESSION['user'], 'stats' => $stats]);
    }
}
