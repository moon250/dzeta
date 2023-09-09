FROM php:fpm-alpine

COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN apk update && apk add --no-cache autoconf build-base make
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install pdo pdo_mysql fileinfo opcache
RUN apk del autoconf build-base

RUN printf '%s%s' ";" "$(cat /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini)" > "/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
RUN printf '%s%s' ";" "$(cat /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini)" > "/usr/local/etc/php/conf.d/docker-php-ext-opcache.ini"

WORKDIR /var/www

EXPOSE 9000
