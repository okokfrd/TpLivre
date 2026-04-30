<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class AuthController
{
    public function __construct(private User $userModel)
    {
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegisterForm(): void
    {
        View::render('auth/register');
    }

    /**
     * Traite l'inscription
     */
    public function register(): void
    {
        $nom      = trim($_POST['nom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nom === '' || $email === '' || $password === '') {
            View::render('auth/register', ['error' => 'Tous les champs sont obligatoires.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            View::render('auth/register', ['error' => 'Adresse email invalide.']);
            return;
        }

        if (strlen($password) < 6) {
            View::render('auth/register', ['error' => 'Le mot de passe doit contenir au moins 6 caractères.']);
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            View::render('auth/register', ['error' => 'Cet email est déjà utilisé.']);
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->create($nom, $email, $hash, 'membre');

        header('Location: index.php?action=login&success=1');
        exit;
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm(): void
    {
        $success = isset($_GET['success']) ? 'Inscription réussie ! Vous pouvez vous connecter.' : '';
        View::render('auth/login', ['success' => $success]);
    }

    /**
     * Traite la connexion
     */
    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            View::render('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
            return;
        }

        // Régénération de l'ID de session pour la sécurité
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'nom'   => $user['nom'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        header('Location: index.php?action=dashboard');
        exit;
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?action=login');
        exit;
    }
}
