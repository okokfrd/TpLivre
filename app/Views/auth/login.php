<?php $pageTitle = 'Connexion'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Club de Lecture</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="main-content">
    <div class="container auth-container">
        <h1>Connexion</h1>
        <p class="auth-subtitle">Club de Lecture</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?action=loginPost">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.fr">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
            </div>

            <button type="submit" class="btn btn-block">Se connecter</button>
        </form>

        <p class="auth-link">Pas encore de compte ? <a href="index.php?action=register">Créer un compte</a></p>
    </div>
</main>
</body>
</html>
