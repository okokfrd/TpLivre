# Base d'authentification PHP 8 (MVC simple)

Cette base couvre les fonctionnalités minimales :
- Inscription
- Connexion (session PHP)
- Déconnexion
- Protection d'une page (dashboard)

## Arborescence

```text
TpLivre/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── HomeController.php
│   ├── Core/
│   │   ├── Auth.php
│   │   ├── Database.php
│   │   └── View.php
│   ├── Models/
│   │   └── User.php
│   └── Views/
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       └── home/
│           └── dashboard.php
├── config/
│   └── config.php
├── database.sql
├── public/
│   └── index.php
└── README.md
```

## Prérequis (WAMP)
- WAMP avec PHP 8.x
- Extension PDO MySQL activée

## Installation
1. Copier le dossier `TpLivre` dans `www` de WAMP.
2. Démarrer Apache et MySQL dans WAMP.
3. Aller sur phpMyAdmin et exécuter le script `database.sql`.
4. Vérifier les paramètres dans `config/config.php` (hôte, base, utilisateur, mot de passe).
5. Ouvrir dans le navigateur :
   - `http://localhost/TpLivre/public/index.php?action=register`
   - puis connexion : `http://localhost/TpLivre/public/index.php?action=login`

## Notes sécurité
- Mots de passe hashés avec `password_hash()`.
- Vérification avec `password_verify()`.
- Requêtes préparées PDO.
- Session régénérée à la connexion (`session_regenerate_id`).
