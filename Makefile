#!Make

DC = docker compose -f docker-compose.yml -f docker-compose.override.yml
EXEC = $(DC) exec php
COMPOSER = $(EXEC) composer

## Enter the application container
php:
	@$(EXEC) sh

## Enter the database container
database:
	@$(DC) exec database psql -Usymfony app

## Install the whole dev environment
install:
	@$(DC) build
	@$(MAKE) start -s
	@$(MAKE) vendor -s
	@$(MAKE) db-reset -s

## Install composer dependencies
vendor:
	@$(COMPOSER) install --optimize-autoloader

## Start the project
start:
	@$(DC) up -d --remove-orphans --no-recreate

## Stop the project
stop:
	@$(DC) stop
	@$(DC) rm -v --force

.PHONY: php database install vendor start stop

## Create/Recreate the database
db-create:
	@$(EXEC) bin/console doctrine:database:drop --force --if-exists -nq
	@$(EXEC) bin/console doctrine:database:create -nq

## Update database schema
db-update:
	@$(EXEC) bin/console doctrine:schema:update --force -nq

## Reset database
db-reset: db-create db-update

.PHONY: db-create db-update db-reset
