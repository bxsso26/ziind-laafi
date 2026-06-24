# 🏠 Ziind Laafi — Plateforme Immobilière

> Projet Intégrateur — Licence 2 IT | ISIG HIGH TEC | Université Aube Nouvelle | 2025-2026

---

## 📋 Description

**Ziind Laafi** (« L'habitat serein » en mooré) est une plateforme web de gestion immobilière développée dans le cadre du projet intégrateur de Licence 2 Informatique. Elle permet la mise en relation entre bailleurs (propriétaires) et clients (acquéreurs ou locataires) via une agence immobilière numérique.

---

## 🚀 Fonctionnalités

### Visiteur anonyme
- Consulter le catalogue des propriétés publiées
- Filtrer par type, usage, option (location/vente) et zone géographique
- Voir la fiche détaillée d'une propriété

### Client (connecté)
- Créer un compte et se connecter
- Ajouter des propriétés en favoris
- Faire des demandes de visite
- Consulter l'historique de ses visites et favoris

### Bailleur (connecté)
- Déposer des annonces immobilières avec photos
- Consulter, modifier et supprimer ses annonces
- Suivre le statut de ses annonces (en attente / publiée / retirée)

### Agent immobilier
- Valider ou refuser les annonces des bailleurs
- Traiter les demandes de visite des clients
- Consulter la liste de ses clients affectés
- Publier directement des annonces d'agence

### Manager (administrateur)
- Gérer tous les comptes utilisateurs (CRUD)
- Affecter un client à un agent
- Accéder au tableau de bord statistique
- Retirer des annonces publiques

---

## 🛠️ Technologies utilisées

| Technologie | Version |
|-------------|---------|
| PHP | 8.4 |
| Laravel | 13.x |
| MySQL | 8.x |
| Tailwind CSS | 4.x (CDN) |
| Bootstrap | 5.3 (partiel) |

---

## ⚙️ Installation

### Prérequis
- WAMP / XAMPP / Laragon installé
- PHP >= 8.1
- Composer installé
- MySQL actif

### Étapes

**1. Cloner ou extraire le projet**
```bash
git clone https://github.com/VOTRE_NOM/ziind-laafi.git
cd ziind-laafi
```

**2. Installer les dépendances PHP**
```bash
composer install
```

**3. Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Configurer la base de données dans `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u_auben_immo
DB_USERNAME=root
DB_PASSWORD=
```

**5. Créer la base de données**

Créez une base de données nommée `u_auben_immo` dans phpMyAdmin ou via MySQL.

Ou importez directement le fichier SQL fourni :
```bash
mysql -u root -p u_auben_immo < database/u_auben_immo.sql
```

**6. Exécuter les migrations et les seeders**
```bash
php artisan migrate
php artisan db:seed
```

**7. Lier le stockage des images**
```bash
php artisan storage:link
```

**8. Lancer le serveur**
```bash
php artisan serve
```

Accédez à l'application sur : **http://127.0.0.1:8000**

---

## 👤 Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Manager | manager@ziindlaafi.com | 12345678 |
| Agent | agent@ziindlaafi.com | 12345678 |
| Bailleur | bailleur@ziindlaafi.com | 12345678 |
| Client | client@ziindlaafi.com | 12345678 |

---

## 🗂️ Structure du projet

```
ziind-laafi/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── PropertyController.php
│   │   │   └── ManagerUserController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Property.php
│       ├── VisitRequest.php
│       └── Favorite.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── properties/
│       ├── agent/
│       ├── bailleur/
│       ├── client/
│       └── managers/
├── routes/
│   └── web.php
└── tests/
    └── Unit/
        ├── PropertyTest.php
        └── UserTest.php
```

---

## 🧪 Tests unitaires

Le projet contient **8 tests unitaires** couvrant la logique métier :

```bash
php artisan test
```

| Test | Description |
|------|-------------|
| test_annonce_bailleur_est_en_attente | Une annonce bailleur est en attente de validation |
| test_annonce_agent_est_publiee | Une annonce agent est directement publiée |
| test_statuts_valides_dune_annonce | Vérifie les statuts valides d'une annonce |
| test_prix_et_superficie_sont_numeriques | Prix et superficie sont des nombres positifs |
| test_types_de_biens_autorises | Vérifie les types de biens autorisés |
| test_roles_autorises | Vérifie les rôles utilisateurs autorisés |
| test_mot_de_passe_est_hashe | Le mot de passe est bien hashé (bcrypt) |
| test_inscription_libre_impossible_pour_manager | Manager et agent ne peuvent pas s'inscrire librement |

---

## 🔐 Sécurité

- Mots de passe hashés avec **bcrypt** (via Laravel Hash)
- Protection **CSRF** sur tous les formulaires
- Protection **XSS** via l'échappement Blade (`{{ }}`)
- **Contrôle d'accès par rôle** via middleware personnalisé
- **Requêtes préparées** via Eloquent ORM (protection injection SQL)
- Photos stockées sur le serveur (filesystem), seul le chemin en base

---

## 👥 Membres du groupe

| Nom | Rôle |
|-----|------|
| OUEDRAOGO KISWENDSIDA AMIIR | Chef de projet |
| YERBANGA HOUSNIA | Développeur Backend |
| OUEDRAOGO BARKWENDE ELVINA SHALOM | Développeur Frontend |
| SENI WILL ALAN TRESOR | Modélisation UML |
| OUATTARA ELZA | Tests & Documentation |

---

## 📄 Licence

Projet académique — ISIG HIGH TEC, Université Aube Nouvelle, Burkina Faso © 2026
