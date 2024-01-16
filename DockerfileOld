FROM php:8.2-fpm as php

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Install dependencies
RUN apt-get update && apt-get install -y curl libpq-dev build-essential \
    zip \
    unzip \
    libonig-dev \
    libzip-dev \
    libgd-dev

# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-external-gd
RUN docker-php-ext-install gd

# COPY ./docker/php/laravel.ini /usr/local/etc/php/conf.d/laravel.ini

WORKDIR /var/www
COPY --chown=www-data:www-data . .

RUN chown -R www-data:www-data .

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["sh", "docker/entrypoint.sh" ]
CMD ["php-fpm"]