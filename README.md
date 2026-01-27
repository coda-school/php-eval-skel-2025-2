# Clone d'un site web type Twitter (Y)

## Description

L'application "**Y**" permet aux utilisateurs de **créer un compte**, de **publier des messages courts**, de **suivre d'autres utilisateurs** et d'**interragir avec les messages courts** (likes).
Il se pourrait que l'application, s'inspirant de Twitter, contienne "quelques" contenus troll.

## Prérequis

* **Docker**
* Se connecter à la **DataBase** (postgres)
  * host: localhost
  * user: app
  * port: 32768
  * mot de passe : !ChangeMe!
* Une bonne dose de **toxicité**

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
Explication des commandes :

`docker compose up -d` : lancement des conteneurs docker

`docker compose run --entrypoint/bin/sh php` : mise en pause des conteneurs

`composer require knplabs/knp-time-bundle` : extension pour les dates relatives

`composer require symfony/mime --ignore-platform-req=ext-http` : extension pour l'upload d'images

`docker compose exec php bash/sh` : sert à passer en invité de commande symfony

`symfony console d:s:u --dump-sql --force` : synchronisation de la database avec les Entités

`symfony console doctrine:fixtures:load` : execution de la fixtures (données de test)

## Guide d'utilisation

L'application s'ouvre sur une **page d'accueil** qui offre soit la possibilité de se **connecter** soit de **créer son compte** (s'inscrire).

Une fois connecté, l'utilisateur est redirigé vers la **page d'accueil inapp**. Cette dernière est composée de divers éléments:
* **menu latéral gauche** qui permet de revenir à la page d'accueil (via l'onglet ou le logo), d'accéder à son profil, ou encore de se déconnecter ;
* un **header** composé du titre du site et d'une barre de recherche (la recherche concerne les messages et non les utilisateurs) ;
* le contenu de la page :
  * une **zone de création de message**, permettant la rédaction jusqu'à 280 caractères et l'incorporation d'une image ;
  * un **thread d'actualité** regroupant les messages des personnes que l'utilisateur suit avec quelques suggestions de messages d'utilisateurs non suivis (20% des messages) ;
  * une **zone "tendance"**, qui regroupe les 5 messages les plus likés de la semaine.

En cliquant sur l'**onglet profil**, l'utilisateur arrive sur sa **page personnelle**, qui contient :
* ses **informations personnelles** avec son pseudo et sa bio ;
* la **liste des utilisateurs qu'il suit** et la **liste des utilisateurs qui le suivent** ;
* la **liste des messages** qu'il a rédigé ;
* le **partage de l'url** (copié dans le presse-papier) de la page de profil via le bouton "share-link" ;
* la **modification des informations personnelles** via le bouton "edit_profil".

En cliquant sur **"edit_profil"**, l'utilisateur pourra:
* mettre à jour son pseudo ;
* modifier sa bio.

Un **message** contient plusieurs actions possibles:
* le fait de liker/unliker via le symbole de coeur ;
* le fait de voir en détail ce message via le "view_data".

En cliquant sur **"view_data"**, l'utilisateur pourra en plus:
* partager l'url (copié dans le presse-papier) du message via le bouton "share_link" ;
* s'il en est l'auteur, il pourra:
  * modifier le message via "edit_toxicity" ;
  * supprimer le message via "delete", attention pas de confirmation c'est une suppression directe.

En cliquant sur **"edit_toxicity"**, l'utilisateur pourra: 
* modifier le message ;
* ajouter/supprimer/modifier l'image via "mise à jour visuelle".

## Codes de connexion

Pour tester l'application, vous pouvez utiliser les **codes** suivants :

mail : alice@coda.test / mot de passe : alice

mail: bob@coda.test / mot de passe : bob

## Détail des fonctionnalités additionnelles

 1. **Affichage de la date relative** :
    
Nous avons installé l'extension twig KnpTimeBundle. Dorénavant, les dates de nos messages s'affichent en mode relatif. ex : il y a 2 heures.

 2. **Système de partage de posts** :

Nous avons ajouté un bouton "share_link" qui permet de copié l'url de la page dans le presse-papier de l'utilisateur. Ce bouton est disponible sur chaque page de visualisation d'un message et sur les pages de profil utilisateur.

 3. **Tweets populaires** :

 Nous avons décidé de créer une partie "Tendance" sur notre page d'accueuil in_app. Cette partie regroupe les 5 messages les plus likés (affichant le nombre total de like depuis la création du message) sur les 7 derniers jours.

 4. **Recherche de message** :

Nous avons ajouté dans notre header une barre de recherche. Elle permet de rechercher n'importe quel message via un mot-clé, sans respecter la casse. La recherche doit obligatoirement contenir un champs non vide et non sans contenu.

 5. **Système de likes** :
    
Chaque message possède un symbole coeur permettant à l'utilisateur de le liker/unliker. Si un utilisateur like un message, cela ajoute une ligne dans notre table "likes". S'il unlike par la suite le message, la colonne "isDeleted" passe à true.
Chaque message affiche également le nombre total de like qu'il a obtenu.

 6. **Ajout d’images aux messages** :

Nous avons ajouté un bouton "ajouter une image" lors de la création d'un message. Les extensions autorisées sont png, jpg, jpeg, gif et la taille maximale autorisée est de 1024k.
Lors de la modification de son message, l'utilisateur à l'oportunité de modifier l'image, la supprimer ou en ajouter une s'il n'y en avait pas.

## Utilisation de l'IA

Nous avons utiliser l'IA a **deux reprises** :
- aide à la création de la fixture ;
- aide à l'affichage de coeur vide/plein en fonction du like de l'utilisateur. En effet, notre repository nous renvoyait un tableau multi-directionnel et nous avons utiliser l'IA pour trouver la bonne syntaxe de récupération de l'information dans ce tableau.

## Contributor

**Auteur** : @CDassé / @Jean-Nicolas21 / @croussey10
