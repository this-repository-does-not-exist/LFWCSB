FROM php:8.2-cli-alpine as php
COPY --from=composer/composer:2-bin /composer /usr/local/bin/composer
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
    && pecl install redis \
    && docker-php-ext-enable redis
WORKDIR /app
