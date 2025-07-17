# Tahap 1: Build Dependencies & Assets
FROM composer:2 as vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --no-scripts --prefer-dist

FROM node:18-alpine as assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY vite.config.js .
COPY resources/ resources/
RUN npm run build

# Tahap 2: Final Image
FROM php:8.2-fpm-alpine
WORKDIR /app

# Install dependensi PHP yang umum untuk Laravel
RUN apk add --no-cache \
      libpng-dev \
      libzip-dev \
      jpeg-dev \
      freetype-dev \
      nginx \
      supervisor \
      && docker-php-ext-configure gd --with-freetype --with-jpeg \
      && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip

# Copy file aplikasi dari direktori lokal
COPY . .

# Copy dependensi dari tahap sebelumnya
COPY --from=vendor /app/vendor/ ./vendor/
COPY --from=assets /app/public/build/ ./public/build/

# Setel kepemilikan file agar web server bisa menulis ke storage dan bootstrap/cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Copy konfigurasi Nginx dan Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Expose port 80 untuk Nginx
EXPOSE 80

# Jalankan entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
