FROM php:8.2-cli
RUN apt-get update -y
RUN apt-get install libyaml-dev -y
RUN pecl install yaml \
    && pecl install xdebug \
    && docker-php-ext-enable yaml \
    && docker-php-ext-enable xdebug
#COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY . /usr/src/config-app
WORKDIR /usr/src/config-app
