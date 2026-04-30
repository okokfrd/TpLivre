<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Book;
use App\Models\Progress;

class BookController
{
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_COVER_SIZE = 2000000; // 2 Mo

    public function __construct(private Book $bookModel, private Progress $progressModel)
    {
    }

    /**
     * Liste de tous les livres
     */
    public function index(): void
    {
        Auth::requireLogin();

        $userId = Auth::getUserId();
        $role   = Auth::getRole();
        $livres = $this->bookModel->getAll();

        // Récupère les progressions de l'utilisateur pour chaque livre
        $bookIds = array_map(fn(array $b): int => (int) $b['id'], $livres);
        $progressions = $this->progressModel->getMapByUserAndBooks($userId, $bookIds);

        View::render('livres/index', [
            'livres'       => $livres,
            'role'         => $role,
            'progressions' => $progressions,
        ]);
    }

    /**
     * Fiche détail d'un livre
     */
    public function show(): void
    {
        Auth::requireLogin();

        $id    = (int) ($_GET['id'] ?? 0);
        $livre = $this->bookModel->findByIdWithStats($id);

        if (!$livre) {
            Auth::forbidden();
        }

        $userId      = Auth::getUserId();
        $progression = $this->progressModel->findByUserAndBook($userId, $id);

        View::render('livres/show', [
            'livre'       => $livre,
            'progression' => $progression,
            'role'        => Auth::getRole(),
        ]);
    }

    /**
     * Formulaire de création
     */
    public function createForm(): void
    {
        Auth::requireRole(['admin']);
        View::render('livres/create');
    }

    /**
     * Traitement de la création
     */
    public function store(): void
    {
        Auth::requireRole(['admin']);

        $data = $this->getFormData();
        if ($data === false) {
            return;
        }

        // Gestion de l'upload de la couverture
        $data['cover_path'] = $this->handleCoverUpload();
        $data['created_by'] = Auth::getUserId();

        $this->bookModel->create($data);

        header('Location: index.php?action=livres');
        exit;
    }

    /**
     * Formulaire d'édition
     */
    public function editForm(): void
    {
        Auth::requireRole(['admin']);

        $id    = (int) ($_GET['id'] ?? 0);
        $livre = $this->bookModel->findById($id);

        if (!$livre) {
            Auth::forbidden();
        }

        View::render('livres/edit', ['livre' => $livre]);
    }

    /**
     * Traitement de la mise à jour
     */
    public function update(): void
    {
        Auth::requireRole(['admin']);

        $id    = (int) ($_POST['id'] ?? 0);
        $livre = $this->bookModel->findById($id);

        if (!$livre) {
            Auth::forbidden();
        }

        $data = $this->getFormData($id);
        if ($data === false) {
            return;
        }

        // Gestion de l'upload de couverture (si une nouvelle est fournie)
        $newCover = $this->handleCoverUpload();
        $data['cover_path'] = $newCover ?: ($livre['cover_path'] ?? '');

        $this->bookModel->update($id, $data);

        header('Location: index.php?action=livres');
        exit;
    }

    /**
     * Suppression d'un livre
     */
    public function delete(): void
    {
        Auth::requireRole(['admin']);

        $id = (int) ($_POST['id'] ?? 0);
        $this->bookModel->delete($id);

        header('Location: index.php?action=livres');
        exit;
    }

    /**
     * Valide et récupère les données du formulaire
     */
    private function getFormData(int $id = 0): array|false
    {
        $titre       = trim($_POST['titre'] ?? '');
        $auteur      = trim($_POST['auteur'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $dateDebut   = $_POST['date_debut'] ?? '';
        $dateFin     = $_POST['date_fin'] ?? '';

        if ($titre === '' || $auteur === '') {
            $view   = $id > 0 ? 'livres/edit' : 'livres/create';
            $params = ['error' => 'Le titre et l\'auteur sont obligatoires.'];
            if ($id > 0) {
                $params['livre'] = [
                    'id'          => $id,
                    'titre'       => $titre,
                    'auteur'      => $auteur,
                    'description' => $description,
                    'date_debut'  => $dateDebut,
                    'date_fin'    => $dateFin,
                ];
            }
            View::render($view, $params);
            return false;
        }

        return [
            'titre'       => $titre,
            'auteur'      => $auteur,
            'description' => $description,
            'date_debut'  => $dateDebut ?: null,
            'date_fin'    => $dateFin ?: null,
        ];
    }

    /**
     * Gère l'upload de la couverture du livre
     */
    private function handleCoverUpload(): string
    {
        if (!isset($_FILES['cover']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $file = $_FILES['cover'];

        // Vérification du type MIME
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_IMAGE_TYPES, true)) {
            return '';
        }

        // Vérification de la taille
        if ((int) $file['size'] > self::MAX_COVER_SIZE) {
            return '';
        }

        // Génération d'un nom unique
        $ext       = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };
        $finalName = 'cover_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/covers/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $finalName)) {
            return 'assets/uploads/covers/' . $finalName;
        }

        return '';
    }
}
