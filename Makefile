#!/bin/bash

# Custom vars
project_name_id=test_
db_username=postgres_test_user
db_password=testing
db_name=test_db

#Executables

init-composer:
	@docker exec -ti $(project_name_id)php composer install
	@docker exec -ti $(project_name_id)php php bin/console doctrine:migration:migrate --no-interaction
up:
	@$(shell docker-compose up -d)
stop:
	@$(shell docker-compose stop)
down:
	@$(shell docker-compose down)
db:
	@docker exec -ti $(project_name_id)db psql postgresql://$(db_username):$(db_password)@localhost/$(db_name)
php:
	@docker exec -ti $(project_name_id)php /bin/bash

