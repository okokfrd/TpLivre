<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Documents du livre</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Documents - <?= htmlspecialchars($livre['titre']) ?></h1>

        <div class="menu">
            <a class="btn btn-secondary" href="index.php?action=livres">Retour livres</a>
        </div>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (($role ?? 'membre') === 'admin' || ($role ?? 'membre') === 'moderateur'): ?>
            <h2>Uploader un PDF</h2>
            <form method="post" action="index.php?action=documentUpload" enctype="multipart/form-data">
                <input type="hidden" name="livre_id" value="<?= (int) $livre['id'] ?>">
                <input type="file" name="pdf" accept="application/pdf" required>
                <button type="submit" class="btn">Envoyer</button>
            </form>
        <?php endif; ?>

        <h2>Liste des documents</h2>
        <?php if (empty($documents)): ?>
            <p>Aucun document.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Taille</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><?= htmlspecialchars($doc['nom_fichier']) ?></td>
                        <td><?= number_format(((int) $doc['taille']) / 1024, 1, ',', ' ') ?> Ko</td>
                        <td><a class="btn" href="index.php?action=documentDownload&id=<?= (int) $doc['id'] ?>">Télécharger</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
