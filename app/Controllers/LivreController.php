<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Livre;

class LivreController
{
    public function __construct(private Livre $livreModel)
    {
    }

    public function index(): void
    {
        Auth::requireLogin();
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        if ($role === 'admin') {
            $livres = $this->livreModel->getAll();
        } else {
            $livres = $this->livreModel->getAllByUserId($userId);
        }

        View::render('livres/index', ['livres' => $livres, 'role' => $role]);
    }

    public function createForm(): void
    {
        Auth::requireLogin();
        View::render('livres/create');
    }

    public function store(): void
    {
        Auth::requireLogin();

        $data = $this->getFormData();
        if ($data === false) {
            return;
        }

        $data['user_id'] = (int) $_SESSION['user']['id'];
        $this->livreModel->create($data);

        header('Location: index.php?action=livres');
        exit;
    }

    public function editForm(): void
    {
        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];
        $livre = $this->livreModel->findByIdAndUserId($id, $userId);

        if (!$livre) {
            header('Location: index.php?action=livres');
            exit;
        }

        View::render('livres/edit', ['livre' => $livre]);
    }

    public function update(): void
    {
        Auth::requireLogin();

        $id = (int) ($_POST['id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];

        if (!$this->livreModel->findByIdAndUserId($id, $userId)) {
            header('Location: index.php?action=livres');
            exit;
        }

        $data = $this->getFormData($id);
        if ($data === false) {
            return;
        }

        $this->livreModel->update($id, $userId, $data);

        header('Location: index.php?action=livres');
        exit;
    }

    public function delete(): void
    {
        Auth::requireLogin();

        $id = (int) ($_POST['id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        if ($role === 'admin') {
            $this->livreModel->deleteById($id);
        } else {
            $this->livreModel->delete($id, $userId);
        }

        header('Location: index.php?action=livres');
        exit;
    }

    private function getFormData(int $id = 0): array|false
    {
        $titre = trim($_POST['titre'] ?? '');
        $auteur = trim($_POST['auteur'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';

        if ($titre === '' || $auteur === '' || $dateDebut === '' || $dateFin === '') {
            $view = $id > 0 ? 'livres/edit' : 'livres/create';
            $params = ['error' => 'Titre, auteur, date de début et date de fin sont obligatoires.'];
            if ($id > 0) {
                $params['livre'] = [
                    'id' => $id,
                    'titre' => $titre,
                    'auteur' => $auteur,
                    'description' => $description,
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                ];
            }
            View::render($view, $params);
            return false;
        }

        return [
            'titre' => $titre,
            'auteur' => $auteur,
            'description' => $description,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ];
    }
}
