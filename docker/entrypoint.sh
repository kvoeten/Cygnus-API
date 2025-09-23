#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Run database migrations and generate API documentation.
# The 'depends_on' in docker-compose ensures the database is ready.
echo "Running startup commands..."
php artisan migrate --force
php artisan swagger-lume:generate

# Execute the main container command (in this case, "php-fpm").
echo "Starting PHP-FPM..."
exec "$@"