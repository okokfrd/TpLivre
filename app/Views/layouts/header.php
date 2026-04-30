<?php
// Récupération du rôle et du nom de l'utilisateur connecté
$currentUser = $_SESSION['user'] ?? null;
$currentRole = $currentUser['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Club de Lecture') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php if ($currentUser): ?>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="index.php?action=dashboard">Club de Lecture</a>
    </div>
    <div class="navbar-links">
        <a href="index.php?action=dashboard" class="nav-link">Dashboard</a>
        <a href="index.php?action=livres" class="nav-link">Livres</a>
        <a href="index.php?action=sessions" class="nav-link">Sessions</a>
        <?php if ($currentRole === 'admin'): ?>
            <a href="index.php?action=adminUsers" class="nav-link">Utilisateurs</a>
        <?php endif; ?>
    </div>
    <div class="navbar-user">
        <span class="user-info"><?= htmlspecialchars($currentUser['nom']) ?> <span class="badge badge-<?= $currentRole ?>"><?= htmlspecialchars($currentRole) ?></span></span>
        <a href="index.php?action=logout" class="btn btn-sm btn-danger">Déconnexion</a>
    </div>
</nav>
<?php endif; ?>

<main class="main-content">
