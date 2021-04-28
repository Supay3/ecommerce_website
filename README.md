# Site d'ecommerce

## Description

Ce site est un petit projet commencé il y a environ 1 mois sur la base d'un autre projet, il s'agit d'un site d'ecommerce complet avec une interface
d'admnistration, des utilisateurs, des produits etc...

Il est possible de faire des recherches de produit en fonction de leur catégorie ou de leur type ainsi que du prix maximal que l'on veut,
de commander sans s'inscrire ou bien de s'inscrire si l'on veut sauvegarder notre adresse et notre profil pour d'éventuelles futures commandes.

Le site dispose d'un système de traductions, chaque produit peut être (depuis l'administration) enregistré en autant de langues que l'on veut,
pour être plus précis nous pouvons tout traduire.

## Les bundles utilisés

- EasyAdmin : pour la partie administration, ce Bundle est facile à prendre en main et permet de faire le travail, il manque juste la customisation
d'un dashboard digne de ce nom,
- WebpackEncore : pour l'unification des fichiers JS et CSS, pour l'instant ce n'est pas très utile étant donné que le site ne dispose que de JS,
- VichUploader : pour les images,
- DoctrineFixtures : pour créer une éventuelle démo plus rapidement, le code n'a pas été fait pour l'instant si ce n'est pour un utilisateur.

## L'api de paiement

J'ai utilisé `Stripe` en Api de paiement ce qui permet de payer simplement via l'api sans stocker de données sensibles

## Le code plus généralement

Le système fonctionne sur deux Class en particulier qui sont le OrderService et le CartService qui permettent de gérer respectivement les commandes et le panier,
les deux fonctionnent en harmonie et sont réutilisables à condition de changer certaines choses au niveau des use

## Pour l'essayer

Il vous faudra `yarn` et `composer`, une fois le projet cloné il faudra vous rendre dans le repértoire du projet et utiliser les commandes :
```
$ composer install
$ yarn install
```
Il faudra ensuite initialiser les variables d'environnement dans le fichier `.env`, initialiser la base de données etc...

Enjoy 😊
