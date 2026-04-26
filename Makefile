SHELL := /bin/zsh

.PHONY: up down build logs shell composer npm artisan install init laravel-key migrate seed migrate-seed test

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache

logs:
	docker compose logs -f

shell:
	docker compose exec app sh

composer:
	docker compose exec app composer $(cmd)

npm:
	docker compose exec app npm $(cmd)

artisan:
	docker compose exec app php artisan $(cmd)

install:
	docker compose exec app composer install

init:
	docker compose run --rm app composer create-project laravel/laravel .

laravel-key:
	docker compose exec app php artisan key:generate

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

migrate-seed:
	docker compose exec app php artisan migrate --seed

test:
	docker compose exec app php artisan test
