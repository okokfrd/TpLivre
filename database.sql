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
