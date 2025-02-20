# Projet Portfolio - Gestion des Utilisateurs et des Compétences

###### Ce projet n'a pas de css par manque temps mais a normalement un bon back (je crois)
###### Ce projet a été réalisé par Julien Dante

## Présentation du Projet

Ce projet est une application web développée en PHP & MySQL permettant aux utilisateurs de :

-  Gérer leur profil (inscription, connexion, mise à jour des informations).
-  Ajouter et modifier leurs compétences parmi celles définies par un administrateur.
-  Ajouter et gérer leurs projets (titre, description, image et lien).
-  Un administrateur peut gérer les compétences disponibles.

## Fonctionnalités Implémentées

### Authentification & Gestion des Comptes

-  Inscription avec validation des champs
-  Connexion sécurisée avec sessions et option "Se souvenir de moi"
-  Gestion des rôles (Admin / Utilisateur)
-  Mise à jour des informations utilisateur
-  Réinitialisation du mot de passe
-  Déconnexion sécurisée

### Gestion des Compétences

-  L’administrateur peut gérer les compétences proposées
-  Un utilisateur peut sélectionner ses compétences parmi celles disponibles
-  Niveau de compétence défini sur une échelle (débutant → expert)

### Gestion des Projets

-  Ajout, modification et suppression de projets
-  Chaque projet contient : Titre, Description, Image, Lien externe
-  Upload sécurisé des images avec restrictions de format et taille
-  Affichage structuré des projets

### Sécurité

-  Protection contre XSS, CSRF et injections SQL
-  Hachage sécurisé des mots de passe
-  Gestion des erreurs utilisateur avec affichage des messages et conservation des champs remplis
-  Expiration automatique de la session après inactivité

## Installation et Configuration

### Prérequis

- Serveur local (XAMPP, WAMP, etc.)
- PHP 8.x et MySQL
- Un navigateur moderne

### Étapes d’Installation

1. Cloner le projet sur votre serveur local :

   ```sh
   git clone https://github.com/Juliendnte/Php-Port-Folio.git
   cd Php-Port-Folio
   ```

2. Importer la base de données :

    - dans  ```` /src/config/database.sql ````

3. Configurer la connexion à la base de données :
   Créer un `.env` comme `.env.example` :

   ```.dotenv
   DB_HOST=localhost
   DB_NAME=projetb2
   DB_USER=projetb2
   DB_PASS=password
   DB_PORT=3306

   BASE_URL=http://localhost:8080
   ```

4. Démarrer le serveur PHP et tester l'application :

   ```sh
   composer install
   composer dump-autoload
   composer serve
   ```

   Puis accéder à l'application via `http://localhost:8080`

## Comptes de Test

### Compte Administrateur

- **Email** : julien.dante@ynov.com
- **Mot de passe** : password

### Compte Utilisateur

- **Email** : kantin.fagniart@ynov.com
- **Mot de passe** : password
- 
- **Email** : nathanael.pivot@ynov.com
- **Mot de passe** : password

## Technologies Utilisées

- **Backend** : **PHP**
- **Frontend** : **Html**

## Licence

Ce projet est sous licence MIT.
