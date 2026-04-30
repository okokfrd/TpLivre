# Base d'authentification PHP 8 (MVC simple)

Cette base couvre les fonctionnalités minimales :
- Inscription
- Connexion (session PHP)
- Déconnexion
- Protection d'une page (dashboard)
- CRUD Livres (ajouter, lister, modifier, supprimer)
- Gestion simple des rôles (admin / membre)
- Système d'avis et notes (1 à 5)
- Gestion de la progression de lecture (0 à 100)
- Dashboard amélioré avec statistiques simples

## Arborescence

```text
TpLivre/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── AvisController.php
│   │   ├── HomeController.php
│   │   ├── LivreController.php
│   │   └── ProgressionController.php
│   ├── Core/
│   │   ├── Auth.php
│   │   ├── Database.php
│   │   └── View.php
│   ├── Models/
│   │   ├── Avis.php
│   │   ├── Livre.php
│   │   ├── Progression.php
│   │   └── User.php
│   └── Views/
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── avis/
│       │   └── index.php
│       ├── home/
│       │   └── dashboard.php
│       └── livres/
│           ├── create.php
│           ├── edit.php
│           └── index.php
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
6. Après connexion, aller dans `Gérer mes livres` pour tester le CRUD (les livres affichés sont ceux de l'utilisateur connecté).

## Notes sécurité
- Mots de passe hashés avec `password_hash()`.
- Vérification avec `password_verify()`.
- Requêtes préparées PDO.
- Session régénérée à la connexion (`session_regenerate_id`).

## Rôles (simple)
- À l'inscription, le rôle créé est `membre`.
- Le dashboard affiche le rôle de l'utilisateur connecté.
- `membre` : gère uniquement ses propres livres.
- `admin` : voit tous les livres et peut supprimer n'importe quel livre.

## Avis et notes
- Un utilisateur peut laisser un seul avis par livre (contrainte unique BDD + logique applicative).
- Un avis contient une note (1 à 5) et un commentaire.
- Sur la page livres, cliquer sur `Avis` pour consulter/ajouter/modifier son avis.

## Progression de lecture
- L'utilisateur peut saisir un pourcentage de progression (0 à 100) pour chaque livre.
- La progression est affichée directement dans la liste des livres.
- Une seule ligne de progression par utilisateur et par livre (mise à jour si elle existe déjà).

## Dashboard (statistiques simples)
- Nombre de livres lus (progression = 100).
- Nombre de livres en cours (progression entre 1 et 99).
- Moyenne des notes données par l'utilisateur.
- Progression moyenne de l'utilisateur.
