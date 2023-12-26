#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env"
    cp .env.example .env
    # case "$APP_ENV" in
    # "local")
    #     echo "Copying .env.example ... "
    #     cp .env.example .env
    # ;;
    # "prod")
    #     echo "Copying .env.prod ... "
    #     cp .env.prod .env
    # ;;
    # esac
else
    echo "env file exists."
fi

# php artisan migrate
php artisan clear
php artisan optimize:clear
#php artisan migrate

# Fix files ownership.
# chown -R www-data:www-data .
# chown -R www-data:www-data /var/www/storage
# chown -R www-data:www-data /var/www/storage/logs
# chown -R www-data:www-data /var/www/storage/framework
# chown -R www-data:www-data /var/www/storage/framework/sessions
# chown -R www-data:www-data /var/www/bootstrap
# chown -R www-data:www-data /var/www/bootstrap/cache
# chown -R www-data:www-data /var/www/vendor

# # Set correct permission.dcu
# chmod -R 777 /var/www/storage
# chmod -R 777 /var/www/storage/logs
# chmod -R 777 /var/www/storage/framework
# chmod -R 777 /var/www/storage/framework/sessions
# chmod -R 777 /var/www/bootstrap
# chmod -R 777 /var/www/bootstrap/cache
# chmod -R 777 /var/www/vendor

php-fpm -D