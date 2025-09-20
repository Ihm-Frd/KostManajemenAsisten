# Gunakan PHP 8.2 + Apache
FROM php:8.2-apache

# Install dependensi OS
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libicu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip pdo_mysql \
    && docker-php-ext-enable intl zip pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Laravel storage permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel via Artisan
CMD php artisan serve --host=0.0.0.0 --port=8000
