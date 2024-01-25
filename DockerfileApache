FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y \
    unzip \
    libonig-dev\
    libzip-dev \
    libpng-dev \
    zip

RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install zip pdo_mysql bcmath mbstring gd

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . .

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
ENTRYPOINT ["/usr/local/bin/entrypoint"]

# Command to start Apache
CMD ["apache2-foreground"]