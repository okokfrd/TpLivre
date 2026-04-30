<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;

class AdminController
{
    public function __construct(private User $userModel)
    {
    }

    /**
     * Liste de tous les utilisateurs
     */
    public function users(): void
    {
        Auth::requireRole(['admin']);

        $users = $this->userModel->getAll();
        View::render('admin/users', ['users' => $users]);
    }

    /**
     * Met à jour le rôle d'un utilisateur
     */
    public function updateRole(): void
    {
        Auth::requireRole(['admin']);

        $userId = (int) ($_POST['user_id'] ?? 0);
        $role   = $_POST['role'] ?? '';

        // Vérification du rôle valide
        if (!in_array($role, ['admin', 'moderateur', 'membre'], true)) {
            header('Location: index.php?action=adminUsers');
            exit;
        }

        // On ne peut pas modifier son propre rôle
        if ($userId === Auth::getUserId()) {
            header('Location: index.php?action=adminUsers');
            exit;
        }

        $this->userModel->updateRole($userId, $role);

        header('Location: index.php?action=adminUsers');
        exit;
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser(): void
    {
        Auth::requireRole(['admin']);

        $userId = (int) ($_POST['user_id'] ?? 0);

        // On ne peut pas supprimer son propre compte
        if ($userId === Auth::getUserId()) {
            header('Location: index.php?action=adminUsers');
            exit;
        }

        $this->userModel->delete($userId);

        header('Location: index.php?action=adminUsers');
        exit;
    }
}
