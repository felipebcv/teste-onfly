#!/bin/sh

cd /var/www

if [ ! -d "vendor" ]; then
  composer install --prefer-dist --no-progress --no-suggest
fi

chown -R www-data:www-data /var/www
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache


php artisan migrate  --seed

php artisan serve --host=0.0.0.0 --port=8000