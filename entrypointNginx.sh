#!/bin/bash

set -e

# Start PHP-FPM
php-fpm &

# Start Nginx
nginx -g "daemon off;"

# Health check for PHP-FPM
until [ "$(curl -s -o /dev/null -w '%{http_code}' http://localhost:9000)" == "200" ]; do
    sleep 1
done

if [ ! -f ".env" ]; then
    echo "Creating env file for env"
    cp .env.example .env
fi

# Continue with the CMD from the Dockerfile
exec "$@"
