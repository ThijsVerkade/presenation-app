FROM serversideup/php:8.3-fpm-nginx

USER root

# Install GD and EXIF
RUN apk add --no-cache libjpeg-turbo-dev libpng-dev freetype-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd exif

WORKDIR /var/www/html

COPY . .

# Generate Laravel key
RUN php artisan key:generate

# Ensure full ownership and permissions AFTER all writes
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rw storage bootstrap/cache

COPY --chmod=755 /docker/services/laravel-reverb /etc/services.d/laravel-reverb

ENV AUTORUN_ENABLED="true" \
    PHP_OPCACHE_ENABLE="1"

EXPOSE 8080 6001

USER www-data
