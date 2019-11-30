PHP_IMAGE = php:7.4-alpine
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app $(PHP_IMAGE)

test: phpstan phpunit

phpstan:
	$(DOCKER_RUN) vendor/bin/phpstan analyze

phpunit:
	$(DOCKER_RUN) vendor/bin/phpunit

.PHONY: test phpstan phpunit
.SILENT: test phpstan phpunit