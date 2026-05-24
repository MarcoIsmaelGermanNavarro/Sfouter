FROM php:8.4-apache

# 1. Instalar dependencias del sistema de una sola vez
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. PERMISO CRÍTICO: Permitir que Composer corra como root
ENV COMPOSER_ALLOW_SUPERUSER=1

# 4. Habilitar mod_rewrite y configurar Apache
RUN a2enmod rewrite
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 5. Copiar archivos y ejecutar instalación
WORKDIR /var/www/html
COPY . .

# 6. Ejecutar Composer
RUN composer install --no-dev --optimize-autoloader

# 7. Ajustar permisos finales
RUN chown -R www-data:www-data /var/www/html