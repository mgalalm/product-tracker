#!/bin/sh
set -e

# php artisan key:generate

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

php artisan migrate:fresh --seed --force

echo "Running migrations..."
# php artisan migrate --force

# # echo "Seeding database..."
# php artisan db:seed --force

exec "$@"
