# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependencies system
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
       pdo_mysql \
       intl \
       zip \
       gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy composer dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Laravel optimization
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Railway akan inject $PORT)
EXPOSE 8000

# Jalankan Laravel pakai port dari Railway
CMD php artisan serve --host=0.0.0.0 --port=$PORT
