-- ============================================================
-- Base de données : TpLivre - Club de Lecture
-- PHP 8.x / PDO / Architecture MVC
-- ============================================================

CREATE DATABASE IF NOT EXISTS tplivre CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tplivreC;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'moderateur', 'membre') NOT NULL DEFAULT 'membre',
    statut TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=actif, 0=inactif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des livres (lectures)
CREATE TABLE IF NOT EXISTS books (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    description TEXT NULL,
    cover_path VARCHAR(255) NULL COMMENT 'Chemin vers l''image de couverture',
    date_debut DATE NULL,
    date_fin DATE NULL,
    created_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_books_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table des documents (PDF liés à un livre)
CREATE TABLE IF NOT EXISTS documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    mime VARCHAR(100) DEFAULT 'application/pdf',
    size INT UNSIGNED DEFAULT 0,
    uploaded_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_documents_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    CONSTRAINT fk_documents_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table des avis (1 avis max par user et par book)
CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    note INT NOT NULL,
    commentaire TEXT NULL,
    is_hidden TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=masqué par modération',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT uc_reviews_user_book UNIQUE (user_id, book_id),
    CONSTRAINT ck_reviews_note CHECK (note >= 1 AND note <= 5)
) ENGINE=InnoDB;

-- Table de progression de lecture
CREATE TABLE IF NOT EXISTS progress (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    pourcentage INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_progress_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT uc_progress_user_book UNIQUE (user_id, book_id),
    CONSTRAINT ck_progress_pct CHECK (pourcentage >= 0 AND pourcentage <= 100)
) ENGINE=InnoDB;

-- Table des sessions (lives / rencontres)
CREATE TABLE IF NOT EXISTS sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT UNSIGNED NOT NULL,
    titre VARCHAR(255) NOT NULL,
    date_heure DATETIME NOT NULL,
    lieu VARCHAR(255) NULL,
    lien VARCHAR(255) NULL COMMENT 'Lien visio si en ligne',
    description TEXT NULL,
    created_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessions_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    CONSTRAINT fk_sessions_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table d'inscription aux sessions
CREATE TABLE IF NOT EXISTS session_attendance (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uc_session_user UNIQUE (session_id, user_id),
    CONSTRAINT fk_att_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_att_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Données de test : un admin par défaut
-- Mot de passe : Admin123!
-- ============================================================
INSERT INTO users (nom, email, password_hash, role, statut) VALUES
('Admin', 'admin@clublecture.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);
-- Note : le hash ci-dessus correspond au mot de passe "password". 
-- En production, créez un compte admin via l'inscription puis changez le rôle en BDD.
