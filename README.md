# P7_01_API

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f60b417aa68b45dcb455bb70d5d0d98a)](https://app.codacy.com/gh/Franck-Dev/P7_01_API?utm_source=github.com&utm_medium=referral&utm_content=Franck-Dev/P7_01_API&utm_campaign=Badge_Grade_Settings)

Projet 7: Creation d'une API REST sous symfony
## Environnement utilisé durant le développement
* Symfony 4.4.1
* Composer 1.11.99
* Bootstrap 4.1.3
* jQuery 3.3.1
* Xampp 5.6.24
    * Apache 2.4.33
    * PHP 7.2.7
    * MariaDB 4.8.2
* REST Client APIsHub de Firefox Developer Edition

## Installation
1. Clonez ou téléchargez le repository GitHub sur le serveur :
```
    git clone https://github.com/Franck-Dev/P7_01_API_.git
```
2. Configurez vos variables d'environnement tel que la connexion à la base de données ou votre serveur SMTP ou adresse mail dans le fichier `.env.local` qui devra être crée à la racine du projet en réalisant une copie du fichier `.env`.

3. Téléchargez et installez les dépendances du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
```
    php bin/console doctrine:database:create
```
5. Créez les différentes tables de la base de données en appliquant les migrations :
```
    php bin/console doctrine:migrations:migrate
```
6. (Optionnel) Installer les fixtures pour avoir une démo de données fictives (conseillé):
```
    php bin/console doctrine:fixtures:load
```
7. Aller dans la base de données, pour récupérer un token client:
```
    Table ApiToken sur une entrée avec IdClient différent de 'null'
```
8. Pour tester les requettes avec documentation, si vous êtes sur votre localhost :
```
    http://127.0.0.1:8001/api/doc
```
9. Cliquer sur "Authorize" à droite :
```
    Coller le token dans la case pour identifier l'APP_FRONTEND du Client
```
10. Une fois authentifié, vous pouvez vous inscrire dans la base du client :
```
    Vous serez enregistré en tant que user, donc avec des accès limités
```
11. Pour tester les requettes en tant que Admin Client ou Super Admin
```
    Modifier votre rôle dans la table 'User' en "ROLE_CLIENT" ou "ROLE_ADMIN"
```
