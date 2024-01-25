FROM php:8.2-cli
RUN apt-get update -y
RUN apt-get install libyaml-dev -y
RUN pecl install yaml \
    && docker-php-ext-enable yaml
COPY . /usr/src/config-app
WORKDIR /usr/src/config-app
