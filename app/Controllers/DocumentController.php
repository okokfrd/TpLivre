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

        if ((int) $file['size'] > self::MAX_FILE_SIZE) {
            $this->renderWithError($livreId, 'Fichier trop volumineux (max 5 Mo).');
            return;
        }

        $mime = mime_content_type($file['tmp_name']);
        if ($mime !== 'application/pdf') {
            $this->renderWithError($livreId, 'Le fichier doit être un PDF.');
            return;
        }

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
        $finalName = time() . '_' . $safeName;
        $uploadDir = dirname(__DIR__, 2) . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $absolutePath = $uploadDir . $finalName;

        if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
            $this->renderWithError($livreId, 'Impossible d\'enregistrer le fichier.');
            return;
        }

        $this->documentModel->create($livreId, $safeName, 'uploads/' . $finalName, (int) $_SESSION['user']['id']);

        header('Location: index.php?action=documents&livre_id=' . $livreId);
        exit;
    }

    public function download(): void
    {
        Auth::requireLogin();

        $documentId = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? 'membre';

        $document = $this->documentModel->findById($documentId);
        if (!$document) {
            Auth::forbidden();
        }

        $livre = ($role === 'admin' || $role === 'moderateur')
            ? $this->livreModel->findById((int) $document['livre_id'])
            : $this->livreModel->findByIdAndUserId((int) $document['livre_id'], $userId);

        if (!$livre) {
            Auth::forbidden();
        }

        $absolutePath = dirname(__DIR__, 2) . '/' . $document['filepath'];

        if (!file_exists($absolutePath)) {
            Auth::forbidden();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($document['filename']) . '"');
        header('Content-Length: ' . filesize($absolutePath));
        readfile($absolutePath);
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
