<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
</head>
<body>
    <h1>Ajouter un livre</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?action=livresStore">
        <label>Titre :</label><br>
        <input type="text" name="titre" required><br><br>

        <label>Auteur :</label><br>
        <input type="text" name="auteur" required><br><br>

        <label>Description :</label><br>
        <textarea name="description" rows="5" cols="40"></textarea><br><br>

        <label>Date début :</label><br>
        <input type="date" name="date_debut" required><br><br>

        <label>Date fin :</label><br>
        <input type="date" name="date_fin" required><br><br>

        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="index.php?action=livres">Retour à la liste</a></p>
</body>
</html>
