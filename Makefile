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

up-new: stop clean ## cleanup all: fresh git, fresh data, fresh containers
	git fetch -a
	git reset --hard origin/$(shell git rev-parse --abbrev-ref HEAD) || true
	cd laravel && cp .env.example .env && composer install
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

# Checkstyle Python

linter: ## run linter (Python)
	cd etl && ruff check

linter-fix: ## run linter and fix (Python)
	cd etl && ruff check --fix

format: ## run formatter (Python)
	cd etl && ruff format
