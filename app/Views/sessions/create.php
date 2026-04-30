<?php $pageTitle = 'Créer une session'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Créer une session</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=sessionStore">
        <div class="form-group">
            <label for="book_id">Livre *</label>
            <select id="book_id" name="book_id" required>
                <option value="">-- Choisir un livre --</option>
                <?php foreach ($livres as $livre): ?>
                    <option value="<?= (int) $livre['id'] ?>"><?= htmlspecialchars($livre['titre']) ?> - <?= htmlspecialchars($livre['auteur']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="titre">Titre de la session *</label>
            <input type="text" id="titre" name="titre" required placeholder="Ex: Discussion chapitre 5">
        </div>

        <div class="form-group">
            <label for="date_heure">Date et heure *</label>
            <input type="datetime-local" id="date_heure" name="date_heure" required>
        </div>

        <div class="form-group">
            <label for="lieu">Lieu (physique)</label>
            <input type="text" id="lieu" name="lieu" placeholder="Ex: Bibliothèque municipale">
        </div>

        <div class="form-group">
            <label for="lien">Lien (visioconférence)</label>
            <input type="url" id="lien" name="lien" placeholder="https://meet.google.com/...">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3" placeholder="Détails de la session..."></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Créer la session</button>
            <a href="index.php?action=sessions" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
