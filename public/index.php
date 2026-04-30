<?php

/**
 * Point d'entrée de l'application - Routeur principal
 * Club de Lecture - Projet BTS SIO SLAM
 * 
 * Architecture MVC en PHP POO avec PDO
 */

declare(strict_types=1);

// Démarrage de la session
session_start();

// Autoloader PSR-4 simplifié
spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

// Import des classes
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\BookController;
use App\Controllers\ReviewController;
use App\Controllers\ProgressController;
use App\Controllers\DocumentController;
use App\Controllers\SessionController;
use App\Controllers\AdminController;
use App\Core\Database;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Models\Progress;
use App\Models\Document;
use App\Models\Dashboard;
use App\Models\ReadingSession;
use App\Models\SessionAttendance;

// Chargement de la configuration
$config = require __DIR__ . '/../config/config.php';

// Connexion à la base de données via PDO
$pdo = Database::getConnection($config['db']);

// Instanciation des modèles
$userModel       = new User($pdo);
$bookModel       = new Book($pdo);
$reviewModel     = new Review($pdo);
$progressModel   = new Progress($pdo);
$documentModel   = new Document($pdo);
$dashboardModel  = new Dashboard($pdo);
$sessionModel    = new ReadingSession($pdo);
$attendanceModel = new SessionAttendance($pdo);

// Instanciation des contrôleurs
$authController     = new AuthController($userModel);
$homeController     = new HomeController($dashboardModel, $sessionModel);
$bookController     = new BookController($bookModel, $progressModel);
$reviewController   = new ReviewController($reviewModel, $bookModel);
$progressController = new ProgressController($progressModel, $bookModel);
$documentController = new DocumentController($documentModel, $bookModel);
$sessionController  = new SessionController($sessionModel, $attendanceModel, $bookModel);
$adminController    = new AdminController($userModel);

// Récupération de l'action demandée
$action = $_GET['action'] ?? 'login';

// Routage des actions
switch ($action) {

    // ==================== AUTHENTIFICATION ====================
    case 'register':
        $authController->showRegisterForm();
        break;

    case 'registerPost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            header('Location: index.php?action=register');
        }
        break;

    case 'login':
        $authController->showLoginForm();
        break;

    case 'loginPost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            header('Location: index.php?action=login');
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    // ==================== DASHBOARD ====================
    case 'dashboard':
        $homeController->dashboard();
        break;

    // ==================== LIVRES ====================
    case 'livres':
        $bookController->index();
        break;

    case 'livresCreate':
        $bookController->createForm();
        break;

    case 'livresStore':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookController->store();
        } else {
            header('Location: index.php?action=livresCreate');
        }
        break;

    case 'livresEdit':
        $bookController->editForm();
        break;

    case 'livresUpdate':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookController->update();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    case 'livresDelete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookController->delete();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    // ==================== PROGRESSION ====================
    case 'progressionSave':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $progressController->save();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    // ==================== AVIS ====================
    case 'avis':
        $reviewController->showByBook();
        break;

    case 'avisSave':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewController->save();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    case 'avisDelete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewController->delete();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    case 'avisToggleHidden':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewController->toggleHidden();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    // ==================== DOCUMENTS ====================
    case 'documents':
        $documentController->index();
        break;

    case 'documentUpload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documentController->upload();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    case 'documentDownload':
        $documentController->download();
        break;

    case 'documentDelete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documentController->deleteDoc();
        } else {
            header('Location: index.php?action=livres');
        }
        break;

    // ==================== SESSIONS ====================
    case 'sessions':
        $sessionController->index();
        break;

    case 'sessionCreate':
        $sessionController->createForm();
        break;

    case 'sessionStore':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionController->store();
        } else {
            header('Location: index.php?action=sessions');
        }
        break;

    case 'sessionRegister':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionController->register();
        } else {
            header('Location: index.php?action=sessions');
        }
        break;

    case 'sessionUnregister':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionController->unregister();
        } else {
            header('Location: index.php?action=sessions');
        }
        break;

    case 'sessionDelete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionController->delete();
        } else {
            header('Location: index.php?action=sessions');
        }
        break;

    // ==================== ADMIN ====================
    case 'adminUsers':
        $adminController->users();
        break;

    case 'adminUpdateRole':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->updateRole();
        } else {
            header('Location: index.php?action=adminUsers');
        }
        break;

    case 'adminDeleteUser':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->deleteUser();
        } else {
            header('Location: index.php?action=adminUsers');
        }
        break;

    // ==================== DEFAULT ====================
    default:
        header('Location: index.php?action=login');
        break;
}
