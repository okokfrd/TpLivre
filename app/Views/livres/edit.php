<?php $pageTitle = 'Modifier un livre'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Modifier le livre</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=livresUpdate" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int) $livre['id'] ?>">

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($livre['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="cover">Couverture (JPG, PNG ou WebP - max 2 Mo)</label>
            <?php if (!empty($livre['cover_path'])): ?>
                <div class="current-cover">
                    <img src="<?= htmlspecialchars($livre['cover_path']) ?>" alt="Couverture actuelle" style="max-height: 120px; border-radius: 6px;">
                    <p class="text-small text-muted">Couverture actuelle. Uploadez une nouvelle image pour la remplacer.</p>
                </div>
            <?php endif; ?>
            <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_debut">Date de début</label>
                <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($livre['date_debut'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="date_fin">Date de fin</label>
                <input type="date" id="date_fin" name="date_fin" value="<?= htmlspecialchars($livre['date_fin'] ?? '') ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Mettre à jour</button>
            <a href="index.php?action=livres" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
