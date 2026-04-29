<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Database;
use App\Models\User;

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
$homeController = new HomeController();

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

    case 'logout':
        $authController->logout();
        break;

    default:
        header('Location: index.php?action=login');
        break;
}
