# Use a PHP 7.4 FPM image, which is compatible with Lumen 8.x
FROM php:7.4-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies required by the application
RUN apk update && apk add --no-cache \
    build-base \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files and install dependencies
# This is done in a separate step to leverage Docker's layer caching.
# The vendor directory will only be rebuilt if composer.json or composer.lock changes.
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts --no-autoloader

# Copy the rest of the application code
COPY . .

# Generate the optimized autoloader now that all files are present
RUN composer dump-autoload --optimize --classmap-authoritative --no-scripts

# Create bootstrap/cache directory and set correct permissions for storage and cache.
RUN mkdir -p /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and set up the entrypoint script.
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

# Expose port 9000 and set the default command to start php-fpm
EXPOSE 9000
CMD ["php-fpm"]
