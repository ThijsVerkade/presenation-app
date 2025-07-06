FROM serversideup/php:8.2-fpm-nginx

USER root

WORKDIR /var/www/html

COPY . .

# Install Laravel dependencies and setup
RUN php artisan key:generate \
 && chmod -R 775 storage bootstrap/cache \
 && chown -R www-data:www-data .

COPY --chmod=755 /docker/services/laravel-reverb /etc/services.d/laravel-reverb

ENV AUTORUN_ENABLED="true" \
    PHP_OPCACHE_ENABLE="1"

EXPOSE 8080 6001

USER www-data
