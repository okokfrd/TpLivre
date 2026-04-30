<?php $pageTitle = 'Ajouter un livre'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Ajouter un livre</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=livresStore" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" required>
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="cover">Couverture (JPG, PNG ou WebP - max 2 Mo)</label>
            <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_debut">Date de début</label>
                <input type="date" id="date_debut" name="date_debut">
            </div>
            <div class="form-group">
                <label for="date_fin">Date de fin</label>
                <input type="date" id="date_fin" name="date_fin">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Enregistrer</button>
            <a href="index.php?action=livres" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
