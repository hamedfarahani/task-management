# Makefile for running Docker Compose

# Define the path to the Docker Compose binary
DOCKER_COMPOSE := /usr/bin/docker-compose

# Define the default target
default: up


# Target to rebuild and restart the Docker containers
build:
	$(DOCKER_COMPOSE) up -d --build

# Target to stop and remove the Docker containers
down:
	$(DOCKER_COMPOSE) down

# Target to restart the Docker containers
restart:
	$(DOCKER_COMPOSE) restart

# Target to bring up the Docker containers
up:
	$(DOCKER_COMPOSE) up -d

# Target to view the logs of the Docker containers
logs:
	$(DOCKER_COMPOSE) logs -f

# Target to run a specific Docker Compose command
# Usage: make exec service=service-name command="your-command"
exec:
	$(DOCKER_COMPOSE) exec $(service) $(command)

# Target to list all running containers
ps:
	$(DOCKER_COMPOSE) ps

# Target to clean up unused volumes
clean:
	docker volume prune -f

# Target to clean up stopped containers and unused volumes
clean-all:
	$(DOCKER_COMPOSE) down --volumes --remove-orphans
	docker volume prune -f

# Target to show help message
help:
	@echo "Available targets:"
	@echo "  up           - Bring up the Docker containers"
	@echo "  down         - Stop and remove the Docker containers"
	@echo "  restart      - Restart the Docker containers"
	@echo "  build      - Rebuild and restart the Docker containers"
	@echo "  logs         - View the logs of the Docker containers"
	@echo "  exec         - Run a specific command in a container (e.g., make exec service=app command='your-command')"
	@echo "  ps           - List all running containers"
	@echo "  clean        - Clean up unused volumes"
	@echo "  clean-all    - Clean up stopped containers and unused volumes"
	@echo "  help         - Show this help message"

.PHONY: up down restart rebuild logs exec ps clean clean-all help
