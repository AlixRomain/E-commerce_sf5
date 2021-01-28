# Mon-site E-commerce

Ce projet à pour but de devenir un site| E-commerce clef en main 
Il n'y a plus que le front à adapter celon les couleurs de la vitrine client.

Il utilise des bundles tendances et fonctionnel comme:

- Stripe pour ce qui est du paiement, du suivi des achats et de l'état des transactions pour l'admin. 
- Mailjet pour la partie mail. Avec la possibilité de typé ses emails et réaliser des campagnes de pub. 
- EasyAdmin d'easy_Corp pour la gestion admin des produits, des commandes, des livraisons et des utilisateurs.



Il m'a permis d'approfondir mes connaissances de symfony sur les points suivants:

- Le développement et l'élaboration d'une BDD mysqL ( 10 tables)
- La création des controllers et la gestion de leurs vue TWIG.
- La création de formulaire sur mesure celon les besoins de la platforme.
- Le dévellopement d'un DahBoard et des CRUD:CONTROLLER par entity.
- La gestion des fonctionalitées du User accès aux commandes/ Modification Pssword/ Création du compte et des adresse
- Utilisation de bootstrap, de bundle de carrossel et l'ajout de classe pernso en css.
- La création d'un panier et la gestion d'un tunnel d'achat jusqu'à transaction sur stripe
- Lagestion d'upload de ficher à travers de controller implémentant l'EventSubscriberInterface


---
## Procédure d'installation

### 1 - Télécharger et extraire le projet

    wget https://github.com/AlixRomain/E-commerce_sf5
    
### 2 - Installer les dépendances

    composer install

### 3 - Modifier le fichier .env

* Vos identifiants de base de données

> Ligne 32 : DATABASE_URL=mysql://LOGIN:PASSWORD@127.0.0.1:3306/DATABASENAME?serverVersion=5.7

* Votre SMTP

> Ligne 39 : MAILER_URL=smtp://SMTP:465?encryption=ssl&auth_mode=login&username=USERNAME&password=PASSWORD

* Passer de développement à production

> Ligne 17 : APP_ENV=dev par APP_ENV=prod

### 4 - Initialiser la base de données

2 méthodes :

> * Soit utiliser le fichier .sql dans le dossier racine et l'importer dans votre SGBD
> * Soit utiliser les migrations de doctrine et les fixtures


    php bin/console doctrine:migrations:migrate 
    php bin/console doctrine:fixtures:load
