<?php $pageTitle = 'Gestion des utilisateurs'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Gestion des utilisateurs</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int) $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="badge badge-<?= htmlspecialchars($u['role']) ?>">
                            <?= htmlspecialchars($u['role']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <?php if ((int) $u['id'] !== \App\Core\Auth::getUserId()): ?>
                            <!-- Changer le rôle -->
                            <form method="post" action="index.php?action=adminUpdateRole" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="membre" <?= $u['role'] === 'membre' ? 'selected' : '' ?>>Membre</option>
                                    <option value="moderateur" <?= $u['role'] === 'moderateur' ? 'selected' : '' ?>>Modérateur</option>
                                    <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>

                            <!-- Supprimer -->
                            <form method="post" action="index.php?action=adminDeleteUser" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur et toutes ses données ?');">
                                <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">Vous</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
