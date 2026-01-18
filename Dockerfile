FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN apk add --no-cache nano

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
