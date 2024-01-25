build:
	docker build -t vmassalov-config .

test:
	docker run --rm --name vmassalov-config-test vmassalov-config php ./vendor/bin/phpunit
