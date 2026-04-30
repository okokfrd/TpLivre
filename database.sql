CREATE DATABASE IF NOT EXISTS club_lecture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE club_lecture;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'moderateur', 'membre') NOT NULL DEFAULT 'membre'
);

CREATE TABLE IF NOT EXISTS livres (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    description TEXT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_livres_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS avis (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL,
    commentaire TEXT NULL,
    user_id INT UNSIGNED NOT NULL,
    livre_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_avis_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_avis_livre FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE,
    CONSTRAINT uc_avis_user_livre UNIQUE (user_id, livre_id),
    CONSTRAINT ck_avis_note CHECK (note >= 1 AND note <= 5)
);

CREATE TABLE IF NOT EXISTS progression (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pourcentage INT NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    livre_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_progression_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_progression_livre FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE,
    CONSTRAINT uc_progression_user_livre UNIQUE (user_id, livre_id),
    CONSTRAINT ck_progression_pourcentage CHECK (pourcentage >= 0 AND pourcentage <= 100)
);

CREATE TABLE IF NOT EXISTS documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    livre_id INT UNSIGNED NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    uploaded_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_documents_livre FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE,
    CONSTRAINT fk_documents_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    livre_id INT UNSIGNED NOT NULL,
    titre VARCHAR(255) NOT NULL,
    date_heure DATETIME NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_by INT UNSIGNED NOT NULL,
    CONSTRAINT fk_sessions_livre FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE,
    CONSTRAINT fk_sessions_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS session_attendance (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    CONSTRAINT uc_session_user UNIQUE (session_id, user_id),
    CONSTRAINT fk_att_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    CONSTRAINT fk_att_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
