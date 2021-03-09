# New-OVacances

# O'Vacances - Back 
documentation pour faire fonctionner le projet

## Pour installer symfony et les dépendances
Dans le terminal, dans le dossier "New-OVacances/back" :
```
composer install
```

## Pour avoir la BDD, penser à créer le .env.local à la racine du dossier "back" en modifiant les informations sur user - password - db_name correspondant à vos accès Adminer (ou autre) et faire les commandes suivantes dans le terminal
### .env.local
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

### Création de la BDD : 
en LDC : 
```
php bin/console doctrine:database:create
```

### Conception des tables: 
```
php bin/console doctrine:migrations:migrate
```

### Si la migration ne fonctionne pas faire les demarches suivantes
Dans le fichier :
    *vendor\doctrine\migrations\lib\Doctrine\Migrations\Metadata\Storage\TableMetadataStorage.php*

commenter les lignes 191->195

faire la migration en LDC : 
```
php bin/console doctrine:migrations:migrate
```
puis décommenter les lignes précédentes (191->195)

### chargements des fixtures 
en LDC :
```
php bin/console doctrine:fixtures:load
```

## LexikJWTAuthenticationBundle (JWT)
#### Pour récupérer le bundle

https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#getting-started
```
composer install 
``` 
ou
```
php composer.phar require "lexik/jwt-authentication-bundle"
```

#### Pour installer le dossier JWT et avoir les tokens, faire les 3 commandes ci dessous en ldc l'une après l'autre
  ```
  mkdir -p config/jwt
  ```  
(création du dossier JWT dans config)
  ```
  openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
  ```
(il demande 2 fois un mot de passe. On trouve ce mot de passe dans le fichier .env, dans la rubrique 'lexik/jwt-authentication-bundle' à coté de JWT_PASSPHRASE)
  ```
  openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
  ```
(il redemande un mot de passe, remettre le même)

Si tout s'est bien passé, 2 fichiers sont crées dans le dossier JWT et dans chacun il y a une clé hashée.

## Lancer le serveur
dans le dossier "back"
```
symfony server:start --no-tls
```
