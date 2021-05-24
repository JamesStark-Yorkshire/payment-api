FROM php:8-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git libzip-dev zip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www