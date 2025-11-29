# ---- Imagen base con PHP-FPM ----
FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    nginx \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

# Copiar archivo de configuración de Nginx
COPY deploy/nginx.conf /etc/nginx/conf.d/default.conf

# Copiar código del backend dentro del contenedor
COPY . /var/www/html

# Cambiar directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Permisos a Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto (Railway lee esto)
EXPOSE 80

# Comando final: iniciar nginx + PHP-FPM
CMD service nginx start && php-fpm
