<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Review
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Récupère les avis d'un livre (visibles pour les membres, tous pour admin/modérateur)
     */
    public function getByBookId(int $bookId, bool $includeHidden = false): array
    {
        $sql = 'SELECT r.*, u.nom AS auteur_nom
                FROM reviews r
                INNER JOIN users u ON u.id = r.user_id
                WHERE r.book_id = :book_id';

        if (!$includeHidden) {
            $sql .= ' AND r.is_hidden = 0';
        }

        $sql .= ' ORDER BY r.created_at DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['book_id' => $bookId]);
        return $stmt->fetchAll();
    }

    /**
     * Recherche l'avis d'un utilisateur pour un livre
     */
    public function findByUserAndBook(int $userId, int $bookId): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM reviews WHERE user_id = :user_id AND book_id = :book_id LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
        return $stmt->fetch();
    }

    /**
     * Crée un avis
     */
    public function create(int $note, string $commentaire, int $userId, int $bookId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reviews (note, commentaire, user_id, book_id)
             VALUES (:note, :commentaire, :user_id, :book_id)'
        );
        return $stmt->execute([
            'note'        => $note,
            'commentaire' => $commentaire,
            'user_id'     => $userId,
            'book_id'     => $bookId,
        ]);
    }

    /**
     * Met à jour un avis
     */
    public function update(int $id, int $note, string $commentaire): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE reviews SET note = :note, commentaire = :commentaire WHERE id = :id'
        );
        return $stmt->execute(['id' => $id, 'note' => $note, 'commentaire' => $commentaire]);
    }

    /**
     * Supprime un avis (par son propriétaire)
     */
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM reviews WHERE id = :id AND user_id = :user_id');
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }

    /**
     * Masque / affiche un avis (modération)
     */
    public function toggleHidden(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE reviews SET is_hidden = NOT is_hidden WHERE id = :id'
        );
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Recherche un avis par ID
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
