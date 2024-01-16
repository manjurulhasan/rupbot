#!/bin/bash

set -e

if [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --no-scripts --no-autoloader
    composer dump-autoload --optimize
fi
if [ ! -f ".env" ]; then
    echo "Creating env file for env"
    cp .env.example .env
fi
# Fix files ownership.
chown -R www-data:www-data .
# Continue with the CMD from the Dockerfile
exec "$@"