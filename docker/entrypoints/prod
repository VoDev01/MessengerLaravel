#!/bin/bash

php artisan key:generate --force
php artisan optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache

exec "$@"