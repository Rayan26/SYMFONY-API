# SYMFONY-API

Installation

1. Installez les dépendances : `composer install`
2. Lancer les migrations : `php bin/console d:m:m`
3. Jouez les dernière migrations : `php ./bin/console doctrine:migrations:diff`
4. Lancer les dernière migrations : php ./bin/console doctrine:migrations:execute --up 'DoctrineMigrations\\VersionNUMERO_DERNIERE_VERSION'
5. Jouez les fixtures : `php bin/console doctrine:fixtures:load`
6. Lancez le server : `php -S localhost:3000 -t public`

Utilisation du service d'exportation des entités en CSV:

- Lancez le server : `php -S localhost:3000 -t public`

- Aller a l'url localhost:3000/api/entityCSV/[NOM DE L'ENTITÉ A EXPORTER]

  Exemple : localhost:3000/api/entityCSV/HistoricQuestion pour importer l'entité HistoricQuestion


