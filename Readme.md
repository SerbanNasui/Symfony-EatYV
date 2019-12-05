composer create-project symfony/skeleton Symfony-EatYV
composer require annotations twig
composer require symfony/asset
composer require debug --dev

php bin/console server:run

MakerBundle:
composer require maker --dev

Server Setup:
composer require symfony/web-server-bundle --dev

Make controller:
php bin/console make:controller

Make entity:
php bin/console make:entity

composer require doctrine form security validation

php bin/console doctrine:database:create

php bin/console make:migration

php bin/console doctrine:migrations:migrate

php bin/console doctrine:schema:update --force

