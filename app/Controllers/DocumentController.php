<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Document;
use App\Models\Book;

class DocumentController
{
    private const MAX_FILE_SIZE = 5000000; // 5 Mo

    public function __construct(private Document $documentModel, private Book $bookModel)
    {
    }

    /**
     * Liste des documents d'un livre
     */
    public function index(): void
    {
        Auth::requireLogin();

        $bookId = (int) ($_GET['book_id'] ?? 0);
        $livre  = $this->bookModel->findById($bookId);

        if (!$livre) {
            Auth::forbidden();
        }

        $documents = $this->documentModel->getByBookId($bookId);

        View::render('documents/index', [
            'livre'     => $livre,
            'documents' => $documents,
            'role'      => Auth::getRole(),
        ]);
    }

    /**
     * Upload d'un document PDF
     */
    public function upload(): void
    {
        // Seuls admin et modérateur peuvent uploader
        Auth::requireRole(['admin', 'moderateur']);

        $bookId = (int) ($_POST['book_id'] ?? 0);
        $livre  = $this->bookModel->findById($bookId);

        if (!$livre || !isset($_FILES['pdf'])) {
            Auth::forbidden();
        }

        $file = $_FILES['pdf'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->renderWithError($bookId, 'Erreur lors de l\'upload du fichier.');
            return;
        }

        if ((int) $file['size'] > self::MAX_FILE_SIZE) {
            $this->renderWithError($bookId, 'Le fichier est trop volumineux (max 5 Mo).');
            return;
        }

        $mime = mime_content_type($file['tmp_name']);
        if ($mime !== 'application/pdf') {
            $this->renderWithError($bookId, 'Seuls les fichiers PDF sont acceptés.');
            return;
        }

        // Nettoyage du nom de fichier
        $safeName  = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
        $finalName = time() . '_' . $safeName;
        $uploadDir = dirname(__DIR__, 2) . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $absolutePath = $uploadDir . $finalName;

        if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
            $this->renderWithError($bookId, 'Impossible d\'enregistrer le fichier.');
            return;
        }

        $this->documentModel->create(
            $bookId,
            $safeName,
            'uploads/' . $finalName,
            $mime,
            (int) $file['size'],
            Auth::getUserId()
        );

        header('Location: index.php?action=documents&book_id=' . $bookId);
        exit;
    }

    /**
     * Téléchargement sécurisé d'un document
     */
    public function download(): void
    {
        Auth::requireLogin();

        $documentId = (int) ($_GET['id'] ?? 0);
        $document   = $this->documentModel->findById($documentId);

        if (!$document) {
            Auth::forbidden();
        }

        // Vérification que le livre existe
        $livre = $this->bookModel->findById((int) $document['book_id']);
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

    /**
     * Suppression d'un document (admin/modérateur)
     */
    public function deleteDoc(): void
    {
        Auth::requireRole(['admin', 'moderateur']);

        $docId  = (int) ($_POST['doc_id'] ?? 0);
        $bookId = (int) ($_POST['book_id'] ?? 0);

        $document = $this->documentModel->findById($docId);
        if ($document) {
            // Supprimer le fichier physique
            $absolutePath = dirname(__DIR__, 2) . '/' . $document['filepath'];
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
            $this->documentModel->delete($docId);
        }

        header('Location: index.php?action=documents&book_id=' . $bookId);
        exit;
    }

    /**
     * Affiche la page documents avec un message d'erreur
     */
    private function renderWithError(int $bookId, string $error): void
    {
        $livre     = $this->bookModel->findById($bookId);
        $documents = $this->documentModel->getByBookId($bookId);

        View::render('documents/index', [
            'livre'     => $livre,
            'documents' => $documents,
            'role'      => Auth::getRole(),
            'error'     => $error,
        ]);
    }
}
