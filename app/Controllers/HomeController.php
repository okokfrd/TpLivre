<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Dashboard;
use App\Models\ReadingSession;

class HomeController
{
    public function __construct(
        private Dashboard $dashboardModel,
        private ReadingSession $sessionModel
    ) {
    }

    /**
     * Affiche le tableau de bord
     */
    public function dashboard(): void
    {
        Auth::requireLogin();

        $userId = Auth::getUserId();
        $role   = Auth::getRole();

        // Statistiques personnelles
        $stats = [
            'livres_lus'          => $this->dashboardModel->countLivresLus($userId),
            'livres_en_cours'     => $this->dashboardModel->countLivresEnCours($userId),
            'moyenne_notes'       => $this->dashboardModel->moyenneNotes($userId),
            'moyenne_progression' => $this->dashboardModel->moyenneProgression($userId),
        ];

        // Livres en cours avec progression
        $livresEnCours = $this->dashboardModel->getLivresEnCours($userId);

        // Prochaines sessions
        $prochainesSessions = $this->sessionModel->getUpcomingForUser($userId);

        // Statistiques globales (admin et modérateur)
        $statsGlobales = [];
        if (in_array($role, ['admin', 'moderateur'], true)) {
            $statsGlobales = [
                'total_membres'         => $this->dashboardModel->countMembres(),
                'total_livres'          => $this->dashboardModel->countLivres(),
                'total_avis'            => $this->dashboardModel->countAvis(),
                'moyenne_notes_globale' => $this->dashboardModel->moyenneNotesGlobale(),
                'moyenne_prog_globale'  => $this->dashboardModel->moyenneProgressionGlobale(),
            ];
        }

        View::render('home/dashboard', [
            'user'               => $_SESSION['user'],
            'stats'              => $stats,
            'livresEnCours'      => $livresEnCours,
            'prochainesSessions' => $prochainesSessions,
            'statsGlobales'      => $statsGlobales,
            'role'               => $role,
        ]);
    }
}
