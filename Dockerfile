FROM serversideup/php:8.2-fpm-nginx

USER root

WORKDIR /var/www/html

COPY . .

# Install Laravel dependencies and setup
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
 && cp .env.example .env \
 && php artisan key:generate \
 && chmod -R 775 storage bootstrap/cache \
 && chown -R www-data:www-data .

EXPOSE 8080

USER www-data
