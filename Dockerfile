# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependencies system
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl zip pdo_mysql \
    && a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy semua file project ke container
COPY . .

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Expose port Railway
EXPOSE 8080

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
