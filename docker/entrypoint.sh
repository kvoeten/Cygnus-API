#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Check if Passport keys have been generated. If not, generate them.
# This prevents the command from running on every container start.
if [ ! -f /var/www/html/storage/oauth-private.key ]; then
    echo "Passport keys not found. Generating new keys..."
    php artisan passport:install
else
    echo "Passport keys already exist. Skipping generation."
fi

echo "Running other startup commands..."
php artisan migrate --force
php artisan swagger-lume:generate

# Execute the main container command (in this case, "php-fpm").
exec "$@"