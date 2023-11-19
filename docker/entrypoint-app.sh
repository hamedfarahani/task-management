#!/bin/sh

############################################################
# Author: Hamed Farahani <hfarahani185@gmail.com>    #
############################################################

echo "=========================Entrypoint is running========================="

php-fpm
# Wait for MySQL container to start
echo "Waiting for MySQL container to start..."
sleep 15  # Add a delay of 10 seconds (adjust as needed)

echo "==> Repair environment variables"
cp /var/www/.env.example /var/www/.env

echo "composer install"
composer install

echo "composer dum autoload"
composer dump-autoload

echo "==> Start to clear cached data"
php /var/www/artisan config:clear
php /var/www/artisan cache:clear
php /var/www/artisan route:clear
php /var/www/artisan view:clear
php /var/www/artisan clear-compiled
echo "==> Cached cleared successfully"

echo "==> Start to run migrations"
php /var/www/artisan migrate
echo "==> Complete migrations"

echo "==> Start to run seeder"
php /var/www/artisan db:seed
echo "==> Complete seeder"
echo "==> Start reate application key"
php /var/www/artisan key:generate

