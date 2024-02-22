build:
	docker build -t vmassalov-config .

test:
	docker run --rm --name vmassalov-config-test vmassalov-config php ./vendor/bin/phpunit

psr12:
	./vendor/bin/phpcs --standard=PSR12 ./src

phpstan:
	vendor/bin/phpstan analyse --level=9 src
