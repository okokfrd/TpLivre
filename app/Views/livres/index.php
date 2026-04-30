<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes livres</title>
</head>
<body>
    <h1>Mes livres</h1>

    <p>
        <a href="index.php?action=dashboard">Retour dashboard</a> |
        <a href="index.php?action=livresCreate">Ajouter un livre</a> |
        <a href="index.php?action=logout">Déconnexion</a>
    </p>

    <?php if (empty($livres)): ?>
        <p>Aucun livre pour le moment.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Description</th>
                    <th>Date début</th>
                    <th>Date fin</th>
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
                            <a href="index.php?action=livresEdit&id=<?= (int) $livre['id'] ?>">Modifier</a>

                            <form method="post" action="index.php?action=livresDelete" style="display:inline;" onsubmit="return confirm('Supprimer ce livre ?');">
                                <input type="hidden" name="id" value="<?= (int) $livre['id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
