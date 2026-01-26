# Clone d'un site web type Twitter (Y)

## Description

L'application "Y" permet aux utilisateurs de créer un compte, de publier des messages courts, de suivre d'autres utilisateurs et d'interragir avec les messages courts (likes).
Il se pourrait que l'application, s'inspirant de Twitter, contienne "quelques" contenus troll.

## Prérequis

* Docker
* Se connecter à la DataBase (postgres)
  * host: localhost
  * user: app
  * port: 32768
  * mot de passe : !ChangeMe!
* Une bonne dose de toxicité

## Installation

```
docker compose up -d 
docker compose run --entrypoint/bin/sh php
composer require knplabs/knp-time-bundle
composer require symfony/mime --ignore-platform-req=ext-http
docker compose exec php bash/sh
symfony console d:s:u --dump-sql --force
symfony console doctrine:fixtures:load

```

docker compose up -d : lancement des conteneurs docker

docker compose run --entrypoint/bin/sh php : mise en pause des conteneurs

composer require knplabs/knp-time-bundle : extension pour les dates relatives

composer require symfony/mime --ignore-platform-req=ext-http : extension pour l'upload d'images

docker compose exec php bash/sh : sert à passer en invité de commande symfony

symfony console d:s:u --dump-sql --force : synchronisation de la database avec les Entités

symfony console doctrine:fixtures:load : execution de la fixtures (données de test)

## Guide d'utilisation

L'application s'ouvre sur une page d'accueil qui offre soit la possibilité de se connecter soit de créer son compte (s'inscrire).

Une fois connecté, l'utilisateur est redirigé vers la page d'accueil inapp. Cette dernière est composée de divers éléments:
* menu latéral gauche qui permet de revenir à la page d'accueil (via l'onglet ou le logo), d'accéder à son profil, ou encore de se déconnecter
* un header composé du titre du site et d'une barre de recherche (la recherche concerne les messages et non les utilisateurs)
* le contenu de la page :
  * une zone de création de message, permettant la rédaction jusqu'à 280 caractères et l'incorporation d'une image
  * un thread d'actualité regroupant les messages des personnes que l'utilisateur suit avec quelques suggestions de messages d'utilisateurs non suivis
  * une zone "tendance", qui regroupe les 5 messages les plus likés de la semaine

En cliquant sur l'onglet profil, l'utilisateur arrive sur sa page personnelle, qui contient :
* ses informations personnelles avec son pseudo et sa bio
* la liste des utilisateurs qu'il suit et la liste des utilisateurs qui le suivent
* la liste des messages qu'il a rédigé
* le partage de l'url (copié dans le presse-papier) de la page de profil via le bouton "share-link"
* la modification des informations personnelles via le bouton "edit_profil"

En cliquant sur "edit_profil", l'utilisateur pourra:
* mettre à jour son pseudo
* modifier sa bio

Un message contient plusieurs actions possibles:
* le fait de liker/unliker via le symbole de coeur
* le fait de voir en détail ce message via le "view_data"

En cliquant sur "view_data", l'utilisateur pourra en plus:
* partager l'url (copié dans le presse-papier) du message via le bouton "share_link"
* s'il en est l'auteur, il pourra:
  * modifier le message via "edit_toxicity" 
  * supprimer le message via "delete", attention pas de confirmation c'est une suppression directe

En cliquant sur "edit_toxicity", l'utilisateur pourra: 
* modifier le message
* ajouter/supprimer/modifier l'image via "mise à jour visuelle"



## Astuces & remarques

* Supprimer l’exécutable généré :
```
make clean
```

* Les fichiers objets sont supprimés lors du lancement de la commande : ```make build```

* Des erreurs de **buffer** peuvent apparaître pendant l’exécution.


## Contributor

Auteur : @CDasse
