# Projet Symfony

Ce projet est construit avec le framework Symfony. Il inclut l'utilisation de fixtures pour le peuplement de la base de données et utilise Mercure pour la mise à jour en temps réel.

## Prérequis

- PHP 7.4 ou plus
- Composer
- Symfony CLI
- Une base de données (MySQL, PostgreSQL, etc.)
- Serveur Mercure

## Installation

2. Installez les dépendances :
composer install

3. Configurez les variables d'environnement dans le fichier `.env` ou créez un fichier `.env.local` pour surcharger les paramètres (base de données, serveur Mercure, etc.).

## Base de données

1. Créez la base de données si elle n'existe pas :
php bin/console doctrine:database:create

2. Créer les migrations :
php bin/console make:migration

3. Exécutez les migrations :
php bin/console doctrine:migrations:migrate

## Fixtures

Pour peupler la base de données avec des données de test :

php bin/console doctrine:fixtures:load


## Serveur de développement

Pour lancer le serveur de développement :

symfony server:start

## Mercure

Pour utiliser Mercure :

1. Configurez l'URL du hub Mercure dans le fichier `.env` ou `.env.local`.

2. Lancez le serveur Mercure.

3. Utilisez Mercure dans vos contrôleurs ou services pour envoyer des mises à jour en temps réel.

## Tests

Pour exécuter les tests :

php bin/phpunit

## Contribution

Les contributions sont les bienvenues. Veuillez suivre les standards de codage et les directives de contribution de Symfony.

---

Bonne utilisation et développement !
