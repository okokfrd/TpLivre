<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Book
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Récupère tous les livres
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT b.*, u.nom AS createur_nom 
             FROM books b 
             LEFT JOIN users u ON u.id = b.created_by 
             ORDER BY b.id DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Recherche un livre par ID
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouveau livre
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (titre, auteur, description, cover_path, date_debut, date_fin, created_by)
             VALUES (:titre, :auteur, :description, :cover_path, :date_debut, :date_fin, :created_by)'
        );
        $stmt->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour un livre
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE books
             SET titre = :titre, auteur = :auteur, description = :description,
                 cover_path = :cover_path, date_debut = :date_debut, date_fin = :date_fin
             WHERE id = :id'
        );
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Supprime un livre par ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Compte le nombre total de livres
     */
    public function countAll(): int
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM books');
        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupère un livre avec sa note moyenne et la progression moyenne
     */
    public function findByIdWithStats(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT b.*,
                    (SELECT AVG(r.note) FROM reviews r WHERE r.book_id = b.id AND r.is_hidden = 0) AS note_moyenne,
                    (SELECT AVG(p.pourcentage) FROM progress p WHERE p.book_id = b.id) AS progression_moyenne,
                    (SELECT COUNT(*) FROM reviews r WHERE r.book_id = b.id AND r.is_hidden = 0) AS nb_avis
             FROM books b
             WHERE b.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
