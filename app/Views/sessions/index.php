<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sessions</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="top-nav">
            <a class="btn btn-secondary" href="index.php?action=dashboard">Dashboard</a>
            <a class="btn" href="index.php?action=livres">Livres</a>
            <a class="btn btn-danger" href="index.php?action=logout">Logout</a>
        </div>

        <h1>Sessions (rencontres / lives)</h1>

        <div class="menu">
            <a class="btn btn-secondary" href="index.php?action=dashboard">Dashboard</a>
            <a class="btn" href="index.php?action=sessionCreate">Créer une session</a>
        </div>

        <?php if (empty($sessions)): ?>
            <p>Aucune session pour le moment.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Livre</th>
                    <th>Date/Heure</th>
                    <th>Lieu</th>
                    <th>Description</th>
                    <th>Participants</th>
                    <th>Inscription</th>
                </tr>
                <?php foreach ($sessions as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['titre']) ?></td>
                        <td><?= htmlspecialchars($s['livre_titre']) ?></td>
                        <td><?= htmlspecialchars($s['date_heure']) ?></td>
                        <td><?= htmlspecialchars($s['lieu']) ?></td>
                        <td><?= nl2br(htmlspecialchars($s['description'])) ?></td>
                        <td><?= (int) $s['participants'] ?></td>
                        <td>
                            <?php if ($s['registered']): ?>
                                Déjà inscrit
                            <?php else: ?>
                                <form method="post" action="index.php?action=sessionRegister">
                                    <input type="hidden" name="session_id" value="<?= (int) $s['id'] ?>">
                                    <button type="submit" class="btn">S'inscrire</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
