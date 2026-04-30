<?php $pageTitle = 'Documents - ' . htmlspecialchars($livre['titre']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Documents : <?= htmlspecialchars($livre['titre']) ?></h1>
        <a href="index.php?action=livres" class="btn btn-secondary btn-sm">Retour aux livres</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Upload (admin et modérateur uniquement) -->
    <?php if (in_array($role, ['admin', 'moderateur'], true)): ?>
        <div class="card">
            <h2>Uploader un document PDF</h2>
            <form method="post" action="index.php?action=documentUpload" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?= (int) $livre['id'] ?>">
                <div class="form-group">
                    <input type="file" name="pdf" accept="application/pdf" required>
                    <p class="text-small text-muted">Format PDF uniquement, taille max 5 Mo.</p>
                </div>
                <button type="submit" class="btn">Uploader</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Liste des documents -->
    <h2>Documents disponibles (<?= count($documents) ?>)</h2>

    <?php if (empty($documents)): ?>
        <p class="text-muted">Aucun document pour ce livre.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom du fichier</th>
                    <th>Uploadé par</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><?= htmlspecialchars($doc['filename']) ?></td>
                        <td><?= htmlspecialchars($doc['uploaded_by_nom'] ?? 'Inconnu') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($doc['created_at'])) ?></td>
                        <td>
                            <a href="index.php?action=documentDownload&id=<?= (int) $doc['id'] ?>" class="btn btn-sm">Télécharger</a>
                            <?php if (in_array($role, ['admin', 'moderateur'], true)): ?>
                                <form method="post" action="index.php?action=documentDelete" style="display:inline;" onsubmit="return confirm('Supprimer ce document ?');">
                                    <input type="hidden" name="doc_id" value="<?= (int) $doc['id'] ?>">
                                    <input type="hidden" name="book_id" value="<?= (int) $livre['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
