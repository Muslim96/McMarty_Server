#!/bin/sh
set -e
cd /var/www

[ -f .env ] || cp .env.example .env
[ -d vendor ] || composer install --no-dev --optimize-autoloader --no-interaction
grep -q '^APP_KEY=.\+' .env || php artisan key:generate --force

php artisan storage:link 2>/dev/null || true
php artisan migrate --force
if [ "$RUN_SEEDERS" = "true" ]; then php artisan db:seed --force; fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache
exec php-fpm
