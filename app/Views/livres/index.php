<?php $pageTitle = 'Livres'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Livres du Club</h1>
        <?php if ($role === 'admin'): ?>
            <a href="index.php?action=livresCreate" class="btn">+ Ajouter un livre</a>
        <?php endif; ?>
    </div>

    <!-- Barre de recherche instantanée -->
    <div class="form-group">
        <input type="text" id="searchBooks" placeholder="Rechercher un livre (titre, auteur)..." class="search-input">
    </div>

    <?php if (empty($livres)): ?>
        <p class="text-muted">Aucun livre pour le moment.</p>
    <?php else: ?>
        <div class="books-grid" id="booksGrid">
            <?php foreach ($livres as $livre): ?>
                <div class="book-card" data-search="<?= htmlspecialchars(strtolower($livre['titre'] . ' ' . $livre['auteur'])) ?>">
                    <div class="book-card-cover">
                        <?php if (!empty($livre['cover_path'])): ?>
                            <img src="<?= htmlspecialchars($livre['cover_path']) ?>" alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>">
                        <?php else: ?>
                            <div class="book-cover-placeholder">
                                <span><?= htmlspecialchars(mb_substr($livre['titre'], 0, 1)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="book-card-body">
                        <h3 class="book-title"><?= htmlspecialchars($livre['titre']) ?></h3>
                        <p class="book-author"><?= htmlspecialchars($livre['auteur']) ?></p>

                        <?php if (!empty($livre['description'])): ?>
                            <p class="book-desc"><?= htmlspecialchars(mb_substr($livre['description'], 0, 100)) ?><?= mb_strlen($livre['description']) > 100 ? '...' : '' ?></p>
                        <?php endif; ?>

                        <?php if (!empty($livre['date_debut'])): ?>
                            <p class="text-small text-muted">
                                Du <?= date('d/m/Y', strtotime($livre['date_debut'])) ?>
                                <?= !empty($livre['date_fin']) ? 'au ' . date('d/m/Y', strtotime($livre['date_fin'])) : '' ?>
                            </p>
                        <?php endif; ?>

                        <!-- Barre de progression -->
                        <?php $pct = (int) ($progressions[(int) $livre['id']] ?? 0); ?>
                        <div class="progress-section">
                            <label class="text-small">Ma progression : <strong><?= $pct ?>%</strong></label>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?= $pct ?>%"></div>
                            </div>
                            <form method="post" action="index.php?action=progressionSave" class="progress-form">
                                <input type="hidden" name="book_id" value="<?= (int) $livre['id'] ?>">
                                <input type="range" name="pourcentage" min="0" max="100" value="<?= $pct ?>" class="progress-range" oninput="this.nextElementSibling.textContent=this.value+'%'">
                                <span class="range-value"><?= $pct ?>%</span>
                                <button type="submit" class="btn btn-sm">OK</button>
                            </form>
                        </div>

                        <!-- Actions -->
                        <div class="book-actions">
                            <a href="index.php?action=avis&book_id=<?= (int) $livre['id'] ?>" class="btn btn-sm btn-secondary">Avis</a>
                            <a href="index.php?action=documents&book_id=<?= (int) $livre['id'] ?>" class="btn btn-sm btn-secondary">Documents</a>

                            <?php if ($role === 'admin'): ?>
                                <a href="index.php?action=livresEdit&id=<?= (int) $livre['id'] ?>" class="btn btn-sm">Modifier</a>
                                <form method="post" action="index.php?action=livresDelete" style="display:inline;" onsubmit="return confirm('Supprimer ce livre et toutes ses données associées ?');">
                                    <input type="hidden" name="id" value="<?= (int) $livre['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
