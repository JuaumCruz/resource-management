# Makefile for Laravel and Vue.js Application

# Variables
PHP = php
COMPOSER = composer
NPM = npm
ARTISAN = php artisan

# Default target
.DEFAULT_GOAL := help

# Help command
help:
	@echo "Available commands:"
	@echo "  setup        - Initial setup of the project"
	@echo "  install      - Install PHP and npm dependencies"
	@echo "  migrate      - Run database migrations"
	@echo "  seed         - Seed the database"
	@echo "  dev          - Start development servers (backend and frontend)"
	@echo "  clear        - Clear application caches"
	@echo "  test         - Run application tests"
	@echo "  lint         - Run code linting"

# Initial project setup
setup: install configure migrate

# Install dependencies
install:
	$(COMPOSER) install
	$(NPM) install

# Create .env file and generate app key
configure:
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		$(ARTISAN) key:generate; \
	fi

# Run database migrations
migrate:
	$(ARTISAN) migrate

# Seed the database
seed:
	$(ARTISAN) db:seed

# Start development servers
dev:
	@echo "Starting Laravel development server..."
	@$(ARTISAN) serve & \
	echo "Starting Vite development server..." & \
	$(NPM) run dev

# Clear application caches
clear:
	$(ARTISAN) config:clear
	$(ARTISAN) cache:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear

# Run tests
test:
	$(ARTISAN) test

# Stop all running processes
stop:
	@-pkill -f "artisan serve" || true
	@-pkill -f "npm run dev" || true

# Cleanup
clean: stop
	@-rm -rf vendor
	@-rm -rf node_modules
	@-rm -f .env
	@echo "System cleanup finished..."

.PHONY: help setup install configure migrate seed dev clear test lint stop clean
