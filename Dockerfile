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

COPY --chmod=755 /docker/services/laravel-reverb /etc/services.d/laravel-reverb
COPY --chmod=755 /docker/services/web /etc/services.d/web
COPY --chmod=755 /docker/services/queue-worker /etc/services.d/queue-worker

# Copy custom nginx configuration
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Copy custom PHP configuration (renamed to load last)
COPY docker/php.ini /usr/local/etc/php/conf.d/zzz-custom.ini

ENV AUTORUN_ENABLED="true" \
    PHP_OPCACHE_ENABLE="1" \
    PHP_POST_MAX_SIZE="500M" \
    PHP_UPLOAD_MAX_FILE_SIZE="500M" \
    PHP_MAX_EXECUTION_TIME="300" \
    PHP_MEMORY_LIMIT="512M"

EXPOSE 8080 6001

USER www-data
