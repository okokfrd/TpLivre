<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
    <h1>Dashboard</h1>
    <p>Bienvenue <?= htmlspecialchars($user['nom']) ?> (<?= htmlspecialchars($user['role']) ?>)</p>

    <h2>Mes statistiques</h2>
    <ul>
        <li>Nombre de livres lus : <strong><?= (int) $stats['livres_lus'] ?></strong></li>
        <li>Nombre de livres en cours : <strong><?= (int) $stats['livres_en_cours'] ?></strong></li>
        <li>Moyenne des notes données : <strong><?= number_format((float) $stats['moyenne_notes'], 2, ',', ' ') ?></strong> / 5</li>
        <li>Progression moyenne : <strong><?= number_format((float) $stats['moyenne_progression'], 2, ',', ' ') ?></strong> %</li>
    </ul>

    <div class="menu">
        <a class="btn" href="index.php?action=livres">Mes livres</a>
        <a class="btn btn-secondary" href="index.php?action=logout">Se déconnecter</a>
    </div>
    </div>
</body>
</html>
