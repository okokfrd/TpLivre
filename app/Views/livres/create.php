<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="top-nav">
            <a class="btn btn-secondary" href="index.php?action=dashboard">Dashboard</a>
            <a class="btn" href="index.php?action=livres">Livres</a>
            <a class="btn btn-danger" href="index.php?action=logout">Logout</a>
        </div>

    <h1>Ajouter un livre</h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
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

        <button type="submit" class="btn">Enregistrer</button>
    </form>

    <p><a class="btn btn-secondary" href="index.php?action=livres">Retour à la liste</a></p>
    </div>
</body>
</html>
