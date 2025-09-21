# Gunakan PHP 8.2 + Apache
FROM php:8.2-apache

# Install dependency sistem yang dibutuhkan Laravel + Filament
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libicu-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl zip pdo_mysql \
    && a2enmod rewrite

# Atur working directory
WORKDIR /var/www/html

# Copy composer dari image composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy semua project
COPY . .

# Buat folder storage & cache sebelum composer install
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install dependencies PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Generate app key (supaya APP_KEY selalu ada)
RUN php artisan key:generate --force

# Clear dan optimize cache Laravel
RUN php artisan config:clear || true \
    && php artisan cache:clear || true \
    && php artisan route:clear || true \
    && php artisan view:clear || true \
    && php artisan optimize || true

# Set permission agar Laravel bisa nulis ke storage & bootstrap
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port Railway
EXPOSE 8000

# Jalankan Laravel pakai artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
