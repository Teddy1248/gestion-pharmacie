# 🏥 Site de gestion d'une pharmacie

## 📌 Description

Ce projet est une application web développée en PHP permettant de gérer une pharmacie de manière simple et efficace.

L'application permet :

* la gestion des utilisateurs (inscription, connexion, suppression de compte)
* la gestion des produits (ajout, modification, suppression)
* la gestion des clients (ajout, modification, suppression)
* une interface sécurisée avec authentification

Ce projet a été réalisé dans le but de pratiquer le développement web en PHP et la manipulation de bases de données MySQL.

---

## 🚀 Fonctionnalités

### 🔐 Authentification

* Inscription avec email et mot de passe
* Connexion sécurisée
* Déconnexion
* Suppression du compte

### 💊 Gestion des produits

* Ajouter un produit
* Modifier un produit
* Supprimer un produit
* Afficher la liste des produits

### 👤 Gestion des clients

* Ajouter un client
* Modifier un client
* Supprimer un client
* Afficher la liste des clients

---

## 🛠️ Technologies utilisées

* PHP (procédural / MVC)
* MySQL
* HTML5
* CSS3
* PDO (connexion sécurisée à la base de données)

---

## 🗄️ Base de données

### Table `users`

* id
* email
* password
* created_at

### Table `produits`

* id
* nom
* description
* prix
* stock
* created_at

### Table `clients`

* id
* nom
* email
* telephone
* adresse
* created_at

---

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/ton-username/ton-repo.git
cd ton-repo
```

### 2. Créer la base de données

Importer le fichier `script.sql` dans MySQL (phpMyAdmin recommandé).

### 3. Configurer la connexion

Modifier le fichier :

```
Configuration/database.php
```

```php
$host = 'localhost';
$dbname = 'nom_de_la_base';
$username = 'utilisateur';
$password = 'mot_de_passe';
```

### 4. Lancer le serveur

```bash
php -S localhost:8000
```

Puis ouvrir :

```
http://localhost:8000
```

---

## 🌐 Mise en ligne

Le projet est hébergé sur o2switch.

Configuration requise :

* PHP 8+
* MySQL
* accès cPanel

---

## 📁 Structure du projet

```
├── Configuration/
├── Includes/
├── assets/
├── index.php
├── login.php
├── register.php
├── dashboard.php
├── produits.php
├── clients.php
└── ...
```

---

## 🔒 Sécurité

* Utilisation de PDO avec requêtes préparées
* Hash des mots de passe (`password_hash`)
* Vérification des sessions utilisateur
* Protection des routes sensibles

---

## 🎯 Objectifs du projet

* Comprendre le fonctionnement de PHP
* Manipuler une base de données MySQL
* Créer un système d’authentification
* Structurer un projet web
* Déployer une application en ligne

---

## 🚀 Améliorations possibles

* Gestion des ventes
* Gestion du stock en temps réel
* Alertes de stock faible
* Ajout des dates d’expiration
* Interface utilisateur améliorée
* Passage complet en architecture MVC

---

## 👨‍💻 Auteur

Projet réalisé par [Ton Nom]

---

## 📄 Licence

Ce projet est à usage pédagogique. 