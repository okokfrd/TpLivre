<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Bienvenue <?= htmlspecialchars($user['nom']) ?> (<?= htmlspecialchars($user['role']) ?>)</p>

    <p>Page protégée : accessible seulement si connecté.</p>

    <a href="index.php?action=logout">Se déconnecter</a>
</body>
</html>
