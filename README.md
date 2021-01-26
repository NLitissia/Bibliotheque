# Bibliotheque
Réalisation d'un site pour la gestion des prets de livres à la Bibliothéque.
     
Création d'API.CRUD (Ajout/Suppression/Modification).

Utilisation du Bundle EasyAdmin 3.2.

APIPlatform 

Fixtures (Utilisation de Faker)

Authentification (JWT)

Gestion des roles (Admin, Manager , Utilisateur)

***********************************************************************************
#1) Clonez le projet 

#2) Composer install

#3) Création de la base de donnée
- php bin/console doctrine:database:create
 
- php bin/console make:migration

- php bin/console doctrine:migrations:migrate

#4) Lancer le projet en local 

- composer require server --dev

- php bin/console server:run 


