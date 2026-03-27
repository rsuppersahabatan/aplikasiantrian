# ============================================================
# Dockerfile – Aplikasi Antrian (PHP + Apache)
# ============================================================
FROM php:7.4-apache

# Install PHP extensions yang dibutuhkan
RUN apt-get update && apt-get install -y \
        libgd-dev \
        libzip-dev \
        zip \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin semua file aplikasi ke document root
COPY . /var/www/html/

# Set permission agar PHP bisa menulis ke folder data
RUN chown -R www-data:www-data /var/www/html/data \
    && chmod -R 775 /var/www/html/data

# Izinkan .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80
