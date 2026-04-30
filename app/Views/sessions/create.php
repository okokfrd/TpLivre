<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une session</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Créer une session</h1>

        <form method="post" action="index.php?action=sessionStore">
            <label>Livre :</label>
            <select name="livre_id" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($livres as $livre): ?>
                    <option value="<?= (int) $livre['id'] ?>"><?= htmlspecialchars($livre['titre']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Titre de la session :</label>
            <input type="text" name="titre" required>

            <label>Date et heure :</label>
            <input type="datetime-local" name="date_heure" required>

            <label>Lieu :</label>
            <input type="text" name="lieu" required>

            <label>Description :</label>
            <textarea name="description"></textarea>

            <button type="submit" class="btn">Créer</button>
        </form>

        <p><a class="btn btn-secondary" href="index.php?action=sessions">Retour sessions</a></p>
    </div>
</body>
</html>
