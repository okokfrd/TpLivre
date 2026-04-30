<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes livres</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="top-nav">
            <a class="btn btn-secondary" href="index.php?action=dashboard">Dashboard</a>
            <a class="btn" href="index.php?action=livres">Livres</a>
            <a class="btn btn-danger" href="index.php?action=logout">Logout</a>
        </div>

    <h1><?= ($role ?? "membre") === "admin" ? "Tous les livres (admin)" : "Mes livres" ?></h1>

    <div class="menu">
        <a class="btn btn-secondary" href="index.php?action=dashboard">Dashboard</a>
        <a class="btn" href="index.php?action=livresCreate">Ajouter un livre</a>
        <a class="btn btn-secondary" href="index.php?action=logout">Déconnexion</a>
    </div>

    <?php if (empty($livres)): ?>
        <p>Aucun livre pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Description</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Progression</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $livre): ?>
                    <tr>
                        <td><?= htmlspecialchars($livre['titre']) ?></td>
                        <td><?= htmlspecialchars($livre['auteur']) ?></td>
                        <td><?= nl2br(htmlspecialchars($livre['description'])) ?></td>
                        <td><?= htmlspecialchars($livre['date_debut']) ?></td>
                        <td><?= htmlspecialchars($livre['date_fin']) ?></td>
                        <td>
                            <form method="post" action="index.php?action=progressionSave" style="display:inline;">
                                <input type="hidden" name="livre_id" value="<?= (int) $livre['id'] ?>">
                                <input type="number" name="pourcentage" min="0" max="100" value="<?= (int) ($progressions[(int) $livre['id']] ?? 0) ?>" style="width:80px; display:inline-block;"> %
                                <button type="submit" class="btn">OK</button>
                            </form>
                        </td>
                        <td>
                            <a href="index.php?action=livresEdit&id=<?= (int) $livre['id'] ?>">Modifier</a> |
                            <a href="index.php?action=avis&livre_id=<?= (int) $livre['id'] ?>">Avis</a> |
                            <a href="index.php?action=documents&livre_id=<?= (int) $livre['id'] ?>">Documents</a>

                            <form method="post" action="index.php?action=livresDelete" style="display:inline;" onsubmit="return confirm('Supprimer ce livre ?');">
                                <input type="hidden" name="id" value="<?= (int) $livre['id'] ?>">
                                <button type="submit" class="btn">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </div>
</body>
</html>
