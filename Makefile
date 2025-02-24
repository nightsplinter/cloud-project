env           ?= $(shell basename `pwd`)
composefile = ./laravel/docker-compose.yml
sail = cd laravel && ./vendor/bin/sail

.PHONY: help
.DEFAULT_GOAL := help

# Show help message
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
	| sort \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# Docker compose commands

start: ## start containers
	$(sail) up -d

stop: ## stop containers
	$(sail) stop

restart: ## restart containers
	$(sail) restart

rebuild: ## rebuild containers
	make clean
	make start
	$(sail) artisan key:generate
	$(sail) artisan config:cache
	make migrate-fresh

vite-build: ## build frontend
	cd laravel && npm run dev

status: ## show status of containers
	$(sail) ps

up-new: ## cleanup all: fresh git, fresh data, fresh containers
	stop clean
	git fetch -a
	git reset --hard origin/$(shell git rev-parse --abbrev-ref HEAD) || true
	cd laravel && cp .env.example .env && composer install
	cd larastanm && chmod -R g+w storage bootstrap/cache
	cd laravel && npm install
	make rebuild

clean: ## delete container + data volumes
	docker compose -f $(composefile) down --rmi all -v || true

# Migration commands

migrate: ## migrate database
	$(sail) artisan migrate

migrate-fresh: ## setup new database with seeder data
	$(sail) artisan migrate:fresh --seed

# Running Test

test: ## run tests
	$(sail) test

# Code formatting Laravel

pint-formatting: ## fix code formatting of Laravel Code
	cd laravel && ./vendor/bin/pint

show-pint-formatting: ## Show code formatting of Laravel Code
	cd laravel && ./vendor/bin/pint --test

check-larastan: ## check larastan
	cd laravel && ./vendor/bin/phpstan analyse --memory-limit=512M

#################### Local formatting and linting for the local ETL script ####################

linter: ## run linter (Python)
	cd etl && ruff check

linter-fix: ## run linter and fix (Python)
	cd etl && ruff check --fix

format: ## run formatter (Python)
	cd etl && ruff format
