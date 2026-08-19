# syntax=docker/dockerfile:1.7

FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json pnpm-lock.yaml ./
RUN corepack enable && pnpm install --frozen-lockfile
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN pnpm build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --no-autoloader
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database ./database
COPY routes ./routes
RUN composer dump-autoload --no-dev --optimize --no-interaction --no-scripts

FROM php:8.4-fpm-alpine AS app
RUN apk add --no-cache curl-dev icu-dev libxml2-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install -j"$(nproc)" curl dom intl mbstring opcache pdo_mysql xml zip \
    && rm -rf /tmp/*
WORKDIR /var/www/html
COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=frontend /app/public/build ./public/build
COPY docker/entrypoint.sh /usr/local/bin/pito-entrypoint
RUN chmod +x /usr/local/bin/pito-entrypoint \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && rm -f bootstrap/cache/*.php \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache
ENTRYPOINT ["pito-entrypoint"]
CMD ["php-fpm", "-F"]

FROM nginx:1.27-alpine AS web
WORKDIR /var/www/html
COPY public ./public
COPY --from=frontend /app/public/build ./public/build
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
