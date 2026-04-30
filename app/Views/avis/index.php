<?php $pageTitle = 'Avis - ' . htmlspecialchars($livre['titre']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Avis : <?= htmlspecialchars($livre['titre']) ?></h1>
        <a href="index.php?action=livres" class="btn btn-secondary btn-sm">Retour aux livres</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulaire d'avis -->
    <div class="card">
        <h2><?= $monAvis ? 'Modifier mon avis' : 'Laisser un avis' ?></h2>
        <form method="post" action="index.php?action=avisSave">
            <input type="hidden" name="book_id" value="<?= (int) $livre['id'] ?>">

            <div class="form-group">
                <label>Note</label>
                <div class="star-rating" id="starRating">
                    <?php $currentNote = (int) ($monAvis['note'] ?? 0); ?>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= $i <= $currentNote ? 'star-active' : '' ?>" data-value="<?= $i ?>">&#9733;</span>
                    <?php endfor; ?>
                    <input type="hidden" name="note" id="noteInput" value="<?= $currentNote ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" rows="4" placeholder="Partagez votre avis sur ce livre..."><?= htmlspecialchars($monAvis['commentaire'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn"><?= $monAvis ? 'Modifier' : 'Publier' ?></button>

            <?php if ($monAvis): ?>
                <form method="post" action="index.php?action=avisDelete" style="display:inline;">
                    <input type="hidden" name="review_id" value="<?= (int) $monAvis['id'] ?>">
                    <input type="hidden" name="book_id" value="<?= (int) $livre['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer votre avis ?')">Supprimer mon avis</button>
                </form>
            <?php endif; ?>
        </form>
    </div>

    <!-- Liste des avis -->
    <h2>Tous les avis (<?= count($avis) ?>)</h2>

    <?php if (empty($avis)): ?>
        <p class="text-muted">Aucun avis pour ce livre.</p>
    <?php else: ?>
        <?php foreach ($avis as $item): ?>
            <div class="review-card <?= !empty($item['is_hidden']) ? 'review-hidden' : '' ?>">
                <div class="review-header">
                    <strong><?= htmlspecialchars($item['auteur_nom']) ?></strong>
                    <div class="stars-display">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?= $i <= (int) $item['note'] ? 'star-active' : '' ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <span class="text-small text-muted"><?= date('d/m/Y', strtotime($item['created_at'])) ?></span>
                </div>

                <?php if (!empty($item['is_hidden'])): ?>
                    <p class="text-muted"><em>(Avis masqué par la modération)</em></p>
                <?php endif; ?>

                <p class="review-content"><?= nl2br(htmlspecialchars($item['commentaire'])) ?></p>

                <!-- Bouton de modération (admin/modérateur) -->
                <?php if (in_array($role, ['admin', 'moderateur'], true)): ?>
                    <form method="post" action="index.php?action=avisToggleHidden" style="display:inline;">
                        <input type="hidden" name="review_id" value="<?= (int) $item['id'] ?>">
                        <input type="hidden" name="book_id" value="<?= (int) $livre['id'] ?>">
                        <button type="submit" class="btn btn-sm <?= !empty($item['is_hidden']) ? '' : 'btn-danger' ?>">
                            <?= !empty($item['is_hidden']) ? 'Afficher' : 'Masquer' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
