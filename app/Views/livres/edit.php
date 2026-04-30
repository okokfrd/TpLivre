<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un livre</title>
</head>
<body>
    <h1>Modifier un livre</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?action=livresUpdate">
        <input type="hidden" name="id" value="<?= (int) $livre['id'] ?>">

        <label>Titre :</label><br>
        <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required><br><br>

        <label>Auteur :</label><br>
        <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required><br><br>

        <label>Description :</label><br>
        <textarea name="description" rows="5" cols="40"><?= htmlspecialchars($livre['description']) ?></textarea><br><br>

        <label>Date début :</label><br>
        <input type="date" name="date_debut" value="<?= htmlspecialchars($livre['date_debut']) ?>" required><br><br>

        <label>Date fin :</label><br>
        <input type="date" name="date_fin" value="<?= htmlspecialchars($livre['date_fin']) ?>" required><br><br>

        <button type="submit">Mettre à jour</button>
    </form>

    <p><a href="index.php?action=livres">Retour à la liste</a></p>
</body>
</html>
