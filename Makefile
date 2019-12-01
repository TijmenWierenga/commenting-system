PHP_IMAGE = php:7.4-alpine
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app $(PHP_IMAGE)
DOCKER_COMPOSE = docker-compose -f docker-compose.yml -f docker-compose.dev.yml

start: build
	$(DOCKER_COMPOSE) up -d

build:
	DOCKER_BUILDKIT=1 docker build -t tijmenwierenga/commenting-system:latest .

test: phpcs phpstan phpunit

phpcs:
	$(DOCKER_RUN) vendor/bin/phpcs config public src tests --standard=psr12

phpstan:
	$(DOCKER_RUN) vendor/bin/phpstan analyze

phpunit:
	$(DOCKER_RUN) vendor/bin/phpunit

.PHONY: test phpstan phpunit
.SILENT: test phpstan phpunit