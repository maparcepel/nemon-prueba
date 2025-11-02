#!/bin/bash

# Post-build script for Railway deployment

echo "Running post-build script..."

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Post-build script completed."
