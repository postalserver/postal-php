ARG PHP_VERSION=7.4
FROM php:$PHP_VERSION-cli-alpine

RUN apk add git zip unzip autoconf make g++ icu-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j $(nproc) intl

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN adduser -S php

USER php
WORKDIR /package

COPY composer.json ./

RUN composer install

COPY src src
COPY tests tests
COPY ecs.php phpunit.xml phpstan.neon ./

RUN composer test
