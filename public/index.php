<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\LivreController;
use App\Controllers\ProgressionController;
use App\Controllers\AvisController;
use App\Controllers\DocumentController;
use App\Controllers\SessionController;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Avis;
use App\Models\Document;
use App\Models\Livre;
use App\Models\Progression;
use App\Models\User;
use App\Models\Dashboard;
use App\Models\ReadingSession;
use App\Models\SessionAttendance;

session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

$config = require __DIR__ . '/../config/config.php';
$pdo = Database::getConnection($config['db']);

$userModel = new User($pdo);
$authController = new AuthController($userModel);
$dashboardModel = new Dashboard($pdo);
$homeController = new HomeController($dashboardModel);
$livreModel = new Livre($pdo);
$progressionModel = new Progression($pdo);
$livreController = new LivreController($livreModel, $progressionModel);
$progressionController = new ProgressionController($progressionModel, $livreModel);
$avisModel = new Avis($pdo);
$avisController = new AvisController($avisModel, $livreModel);
$documentModel = new Document($pdo);
$documentController = new DocumentController($documentModel, $livreModel);
$sessionModel = new ReadingSession($pdo);
$sessionAttendanceModel = new SessionAttendance($pdo);
$sessionController = new SessionController($sessionModel, $sessionAttendanceModel, $livreModel);

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'register':
        $authController->showRegisterForm();
        break;

    case 'registerPost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
            break;
        }
        header('Location: index.php?action=register');
        break;

    case 'login':
        $authController->showLoginForm();
        break;

    case 'loginPost':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
            break;
        }
        header('Location: index.php?action=login');
        break;

    case 'dashboard':
        $homeController->dashboard();
        break;


    case 'livres':
        $livreController->index();
        break;

    case 'livresCreate':
        $livreController->createForm();
        break;

    case 'livresStore':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $livreController->store();
            break;
        }
        header('Location: index.php?action=livresCreate');
        break;

    case 'livresEdit':
        $livreController->editForm();
        break;

    case 'livresUpdate':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $livreController->update();
            break;
        }
        header('Location: index.php?action=livres');
        break;

    case 'livresDelete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $livreController->delete();
            break;
        }
        header('Location: index.php?action=livres');
        break;








    case 'sessions':
        $sessionController->index();
        break;

    case 'sessionCreate':
        $sessionController->createForm();
        break;

    case 'sessionStore':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionController->store();
            break;
        }
        Auth::forbidden();
        break;

    case 'sessionRegister':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionController->register();
            break;
        }
        Auth::forbidden();
        break;
    case 'documents':
        $documentController->index();
        break;

    case 'documentUpload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documentController->upload();
            break;
        }
        Auth::forbidden();
        break;

    case 'documentDownload':
        $documentController->download();
        break;
    case 'progressionSave':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $progressionController->save();
            break;
        }
        header('Location: index.php?action=livres');
        break;
    case 'avis':
        $avisController->showByLivre();
        break;

    case 'avisSave':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $avisController->save();
            break;
        }
        header('Location: index.php?action=livres');
        break;
    case 'logout':
        $authController->logout();
        break;

    default:
        header('Location: index.php?action=login');
        break;
}
