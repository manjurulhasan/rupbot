FROM php:8.2-fpm as php

# Install dependencies
RUN apt-get update && apt-get install -y curl libpq-dev build-essential \
    zip \
    unzip \
    libonig-dev \
    libzip-dev \
    libgd-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#Mine

# Install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-external-gd
RUN docker-php-ext-install gd

# Set the working directory
COPY . /var/www/app
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app \
    && chmod -R 775 /var/www/app/storage


# install composer
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

# copy composer.json to workdir & install dependencies
COPY composer.json ./
RUN composer install

# Set the default command to run php-fpm
CMD ["php-fpm"]

# RUN ["chmod", "+x", "docker/entrypoint.sh"]
# ENTRYPOINT ["sh", "docker/entrypoint.sh" ]
