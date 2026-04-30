<?php $pageTitle = 'Dashboard'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Tableau de bord</h1>
    <p>Bienvenue <strong><?= htmlspecialchars($user['nom']) ?></strong></p>

    <!-- Statistiques personnelles -->
    <h2>Mes statistiques</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= (int) $stats['livres_lus'] ?></div>
            <div class="stat-label">Livres terminés</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= (int) $stats['livres_en_cours'] ?></div>
            <div class="stat-label">Livres en cours</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format((float) $stats['moyenne_notes'], 1, ',', '') ?>/5</div>
            <div class="stat-label">Ma note moyenne</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format((float) $stats['moyenne_progression'], 1, ',', '') ?>%</div>
            <div class="stat-label">Progression moyenne</div>
        </div>
    </div>

    <!-- Livres en cours -->
    <?php if (!empty($livresEnCours)): ?>
    <h2>Mes lectures en cours</h2>
    <div class="books-grid">
        <?php foreach ($livresEnCours as $livre): ?>
        <div class="book-card-mini">
            <?php if (!empty($livre['cover_path'])): ?>
                <img src="<?= htmlspecialchars($livre['cover_path']) ?>" alt="Couverture" class="book-cover-mini">
            <?php endif; ?>
            <div class="book-card-mini-info">
                <strong><?= htmlspecialchars($livre['titre']) ?></strong>
                <span class="text-muted"><?= htmlspecialchars($livre['auteur']) ?></span>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: <?= (int) $livre['pourcentage'] ?>%"></div>
                </div>
                <span class="text-small"><?= (int) $livre['pourcentage'] ?>%</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Prochaines sessions -->
    <?php if (!empty($prochainesSessions)): ?>
    <h2>Mes prochaines sessions</h2>
    <div class="sessions-list">
        <?php foreach ($prochainesSessions as $session): ?>
        <div class="session-card-mini">
            <strong><?= htmlspecialchars($session['titre']) ?></strong>
            <span class="text-muted"><?= htmlspecialchars($session['livre_titre']) ?></span>
            <span class="text-small"><?= date('d/m/Y H:i', strtotime($session['date_heure'])) ?></span>
            <?php if (!empty($session['lieu'])): ?>
                <span class="text-small"><?= htmlspecialchars($session['lieu']) ?></span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Statistiques globales (admin / modérateur) -->
    <?php if (!empty($statsGlobales)): ?>
    <h2>Statistiques globales</h2>
    <div class="stats-grid">
        <div class="stat-card stat-card-admin">
            <div class="stat-value"><?= (int) $statsGlobales['total_membres'] ?></div>
            <div class="stat-label">Membres inscrits</div>
        </div>
        <div class="stat-card stat-card-admin">
            <div class="stat-value"><?= (int) $statsGlobales['total_livres'] ?></div>
            <div class="stat-label">Livres au total</div>
        </div>
        <div class="stat-card stat-card-admin">
            <div class="stat-value"><?= (int) $statsGlobales['total_avis'] ?></div>
            <div class="stat-label">Avis publiés</div>
        </div>
        <div class="stat-card stat-card-admin">
            <div class="stat-value"><?= number_format((float) $statsGlobales['moyenne_notes_globale'], 1, ',', '') ?>/5</div>
            <div class="stat-label">Note moyenne globale</div>
        </div>
        <div class="stat-card stat-card-admin">
            <div class="stat-value"><?= number_format((float) $statsGlobales['moyenne_prog_globale'], 1, ',', '') ?>%</div>
            <div class="stat-label">Progression globale</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
