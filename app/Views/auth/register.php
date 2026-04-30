<?php $pageTitle = 'Inscription'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Club de Lecture</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="main-content">
    <div class="container auth-container">
        <h1>Inscription</h1>
        <p class="auth-subtitle">Rejoignez le Club de Lecture</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?action=registerPost">
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" required placeholder="Votre nom">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.fr">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Min. 6 caractères" minlength="6">
            </div>

            <button type="submit" class="btn btn-block">Créer mon compte</button>
        </form>

        <p class="auth-link">Déjà un compte ? <a href="index.php?action=login">Se connecter</a></p>
    </div>
</main>
</body>
</html>
