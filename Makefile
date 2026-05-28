SHELL := /bin/bash

.DEFAULT_GOAL := ssh

PROJECT_ROOT := $(CURDIR)
DOCKER_ROOT := $(abspath $(PROJECT_ROOT)/../docker)
APP_SERVICE := tradiz-php
VITE_SERVICE := tradiz-vite
COMPOSE := docker compose

.PHONY: ssh shell vite-shell artisan

ssh:
	cd "$(DOCKER_ROOT)" && $(COMPOSE) exec $(APP_SERVICE) sh

shell: ssh

vite-shell:
	cd "$(DOCKER_ROOT)" && $(COMPOSE) exec $(VITE_SERVICE) sh

artisan:
	@if [ -z "$(CMD)" ]; then \
		echo "Provide CMD, for example: make artisan CMD='route:list'"; \
		exit 1; \
	fi
	cd "$(DOCKER_ROOT)" && $(COMPOSE) exec $(APP_SERVICE) php artisan $(CMD)
