setup:
	@docker-compose build
	@docker-compose up -d --remove-orphans
	@docker-compose run --rm app composer install
	@make start

clean:
	@docker-compose down

start:
	@docker-compose stop
	@docker-compose up -d

stop:
	@docker-compose stop
