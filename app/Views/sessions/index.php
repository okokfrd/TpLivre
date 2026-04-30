<?php $pageTitle = 'Sessions'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Sessions (Lives / Rencontres)</h1>
        <?php if (in_array($role, ['admin', 'moderateur'], true)): ?>
            <a href="index.php?action=sessionCreate" class="btn">+ Nouvelle session</a>
        <?php endif; ?>
    </div>

    <?php if (empty($sessions)): ?>
        <p class="text-muted">Aucune session programmée.</p>
    <?php else: ?>
        <?php foreach ($sessions as $s): ?>
            <div class="session-card">
                <div class="session-header">
                    <h3><?= htmlspecialchars($s['titre']) ?></h3>
                    <span class="badge"><?= htmlspecialchars($s['livre_titre']) ?></span>
                </div>
                <div class="session-details">
                    <p><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($s['date_heure'])) ?></p>
                    <?php if (!empty($s['lieu'])): ?>
                        <p><strong>Lieu :</strong> <?= htmlspecialchars($s['lieu']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($s['lien'])): ?>
                        <p><strong>Lien :</strong> <a href="<?= htmlspecialchars($s['lien']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($s['lien']) ?></a></p>
                    <?php endif; ?>
                    <?php if (!empty($s['description'])): ?>
                        <p><?= nl2br(htmlspecialchars($s['description'])) ?></p>
                    <?php endif; ?>
                    <p class="text-muted"><?= (int) $s['participants'] ?> participant(s)</p>
                </div>

                <div class="session-actions">
                    <?php if ($s['registered']): ?>
                        <span class="badge badge-success">Inscrit</span>
                        <form method="post" action="index.php?action=sessionUnregister" style="display:inline;">
                            <input type="hidden" name="session_id" value="<?= (int) $s['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-secondary">Se désinscrire</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="index.php?action=sessionRegister" style="display:inline;">
                            <input type="hidden" name="session_id" value="<?= (int) $s['id'] ?>">
                            <button type="submit" class="btn btn-sm">S'inscrire</button>
                        </form>
                    <?php endif; ?>

                    <?php if (in_array($role, ['admin', 'moderateur'], true)): ?>
                        <form method="post" action="index.php?action=sessionDelete" style="display:inline;" onsubmit="return confirm('Supprimer cette session ?');">
                            <input type="hidden" name="session_id" value="<?= (int) $s['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Liste des inscrits (admin/modérateur) -->
                <?php if (!empty($s['liste_inscrits'])): ?>
                    <details class="inscrits-details">
                        <summary>Voir les inscrits (<?= count($s['liste_inscrits']) ?>)</summary>
                        <ul>
                            <?php foreach ($s['liste_inscrits'] as $inscrit): ?>
                                <li><?= htmlspecialchars($inscrit['nom']) ?> (<?= htmlspecialchars($inscrit['email']) ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </details>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
