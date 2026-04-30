<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis du livre</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="top-nav">
            <a class="btn btn-secondary" href="index.php?action=dashboard">Dashboard</a>
            <a class="btn" href="index.php?action=livres">Livres</a>
            <a class="btn btn-danger" href="index.php?action=logout">Logout</a>
        </div>

    <h1>Avis - <?= htmlspecialchars($livre['titre']) ?></h1>

    <p><a class="btn btn-secondary" href="index.php?action=livres">Retour aux livres</a></p>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <h2>Laisser / modifier mon avis</h2>
    <form method="post" action="index.php?action=avisSave">
        <input type="hidden" name="livre_id" value="<?= (int) $livre['id'] ?>">

        <label>Note (1 à 5) :</label><br>
        <input type="number" min="1" max="5" name="note" value="<?= htmlspecialchars((string) ($monAvis['note'] ?? '')) ?>" required><br><br>

        <label>Commentaire :</label><br>
        <textarea name="commentaire" rows="5" cols="50"><?= htmlspecialchars($monAvis['commentaire'] ?? '') ?></textarea><br><br>

        <button type="submit" class="btn"><?= $monAvis ? 'Modifier mon avis' : 'Ajouter mon avis' ?></button>
    </form>

    <h2>Liste des avis</h2>
    <?php if (empty($avis)): ?>
        <p>Aucun avis pour ce livre.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($avis as $item): ?>
                <li>
                    <strong><?= htmlspecialchars($item['nom']) ?></strong>
                    - Note : <?= (int) $item['note'] ?>/5<br>
                    <?= nl2br(htmlspecialchars($item['commentaire'])) ?>
                </li>
                <br>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    </div>
</body>
</html>
