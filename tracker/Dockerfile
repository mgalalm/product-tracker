## 1. Composer build stage
FROM composer:2 AS composer

WORKDIR /app

COPY . .

# Install dependencies without dev for production
RUN composer install --no-dev --prefer-dist --ignore-platform-reqs

## 2. Node.js build stage
FROM node:24-alpine AS nodebuilder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
COPY --from=composer /app/vendor ./vendor
RUN npm run build

# 3. PHP-FPM and Nginx stage
FROM php:8.4-fpm-alpine AS app

USER root

RUN apk update && apk add --no-cache \
    nginx \
    bash \
    curl \
    supervisor \
    postgresql-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql intl

RUN rm /etc/nginx/http.d/default.conf
ADD conf/nginx.conf /etc/nginx/http.d/nginx.conf


COPY conf/supervisord.conf /etc/supervisord.conf

RUN adduser -g  laravel -s /bin/bash -D laravel


RUN sed -i 's/user = www-data/user = laravel/g' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/group = www-data/group = laravel/g' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/user nginx/user laravel/g' /etc/nginx/nginx.conf

COPY --from=composer /app /var/www/html
COPY --from=nodebuilder /app/public /var/www/html/public

RUN chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

# execute the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
