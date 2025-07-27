FROM serversideup/php:8.3-fpm-nginx

USER root

# Install GD and EXIF extensions
RUN apt-get update && \
    apt-get install -y libjpeg-dev libpng-dev libwebp-dev libfreetype6-dev && \
    docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp && \
    docker-php-ext-install gd exif && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .

RUN php artisan key:generate

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rw storage bootstrap/cache

RUN touch database/database.sqlite && \
    chown -R www-data:www-data database && \
    chmod -R 775 database

COPY --chmod=755 /docker/services/laravel-reverb /etc/services.d/laravel-reverb

ENV AUTORUN_ENABLED="true" \
    PHP_OPCACHE_ENABLE="1"

EXPOSE 8080 6001

USER www-data
