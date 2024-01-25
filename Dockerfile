FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk --no-cache add \
    git \
    unzip \
    libzip \
    libzip-dev \
    libpng-dev \
    bash \
    openssl \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-install zip pdo_mysql bcmath mbstring gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --chown=www-data:www-data . .
COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

RUN composer install

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache
ENTRYPOINT ["/usr/local/bin/entrypoint"]
EXPOSE 9000
CMD ["php-fpm"]