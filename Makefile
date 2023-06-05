start:
	symfony server:start -d

stop:
	symfony server:stop

install:
	composer install

reset:
	make stop
	make install
	make db
	make start

cc:
	symfony console cache:clear

db:
	symfony console doctrine:database:drop --force
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate --no-interaction
	symfony console doctrine:fixtures:load --no-interaction

migration:
	symfony console make:migration
	symfony console doctrine:migrations:migrate --no-interaction

fixtures:
	symfony console doctrine:fixtures:load --no-interaction

entity:
	symfony console make:entity