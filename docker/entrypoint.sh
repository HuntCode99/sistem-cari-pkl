#!/bin/sh
set -e

# Jalankan migrasi database
php artisan migrate --force

# Cache konfigurasi untuk produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan perintah utama dari Dockerfile (supervisord)
exec "$@"
