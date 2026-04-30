<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Document;
use App\Models\Livre;

class DocumentController
{
    private const MAX_FILE_SIZE = 5000000; // 5 MB

    public function __construct(private Document $documentModel, private Livre $livreModel)
    {
    }

    public function index(): void
    {
        Auth::requireLogin();

        $livreId = (int) ($_GET['livre_id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        $livre = ($role === 'admin' || $role === 'moderateur')
            ? $this->livreModel->findById($livreId)
            : $this->livreModel->findByIdAndUserId($livreId, $userId);

        if (!$livre) {
            Auth::forbidden();
        }

        $documents = $this->documentModel->getByLivreId($livreId);

        View::render('documents/index', [
            'livre' => $livre,
            'documents' => $documents,
            'role' => $role,
        ]);
    }

    public function upload(): void
    {
        Auth::requireLogin();
        $role = $_SESSION['user']['role'] ?? 'membre';

        if (!in_array($role, ['admin', 'moderateur'], true)) {
            Auth::forbidden();
        }

        $livreId = (int) ($_POST['livre_id'] ?? 0);
        $livre = $this->livreModel->findById($livreId);
        if (!$livre || !isset($_FILES['pdf'])) {
            Auth::forbidden();
        }

        $file = $_FILES['pdf'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->renderWithError($livreId, 'Erreur lors de l\'upload.');
            return;
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->renderWithError($livreId, 'Fichier trop volumineux (max 5 Mo).');
            return;
        }

        $mime = mime_content_type($file['tmp_name']);
        if ($mime !== 'application/pdf') {
            $this->renderWithError($livreId, 'Seuls les fichiers PDF sont autorisés.');
            return;
        }

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
        $finalName = time() . '_' . $safeName;
        $targetDir = dirname(__DIR__, 2) . '/storage/documents/';
        $targetPath = $targetDir . $finalName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $this->renderWithError($livreId, 'Impossible d\'enregistrer le fichier.');
            return;
        }

        $this->documentModel->create($safeName, 'storage/documents/' . $finalName, (int) $file['size'], (int) $_SESSION['user']['id'], $livreId);

        header('Location: index.php?action=documents&livre_id=' . $livreId);
        exit;
    }

    public function download(): void
    {
        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        $document = $this->documentModel->findById($id);
        if (!$document) {
            Auth::forbidden();
        }

        $livre = ($role === 'admin' || $role === 'moderateur')
            ? $this->livreModel->findById((int) $document['livre_id'])
            : $this->livreModel->findByIdAndUserId((int) $document['livre_id'], $userId);

        if (!$livre) {
            Auth::forbidden();
        }

        $filePath = dirname(__DIR__, 2) . '/' . $document['chemin'];
        if (!file_exists($filePath)) {
            Auth::forbidden();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($document['nom_fichier']) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    private function renderWithError(int $livreId, string $error): void
    {
        $livre = $this->livreModel->findById($livreId);
        $documents = $this->documentModel->getByLivreId($livreId);
        View::render('documents/index', [
            'livre' => $livre,
            'documents' => $documents,
            'role' => $_SESSION['user']['role'] ?? 'membre',
            'error' => $error,
        ]);
    }
}
