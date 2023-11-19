# Use the official PHP 8.2 FPM image as the base image
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    zlib1g-dev \
    libpq-dev \
    libzip-dev

RUN docker-php-ext-configure  gd \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath gd intl pcntl

# Remove unnecessary packages and clean up
RUN rm -rf /var/cache/apk/*

# Install Composer globally
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Create a non-root user and switch to it
RUN groupadd -g 1000 mo && useradd -u 1000 -g mo -s /bin/sh -m mo
USER mo

# Copy the Laravel application files into the container
COPY --chown=mo:mo . .

# Set appropriate permissions for Laravel
RUN chmod -R ug+rwx storage bootstrap/cache

# Make sure composer.json has the correct permissions
RUN chmod 664 /var/www/composer.json

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

# Define the entrypoint script
RUN chmod 755 ./docker/entrypoint*

ENTRYPOINT ["./docker/entrypoint-app.sh"]
