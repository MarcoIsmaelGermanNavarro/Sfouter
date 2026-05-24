FROM php:8.4-apache

# 1. Instalar dependencias del sistema necesarias para Composer y PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalar extensiones de PHP necesarias
# (Incluimos zip porque el error mencionaba que la extensión zip también faltaba)
RUN docker-php-ext-install pdo pdo_mysql zip

# 3. Instalar Composer desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# 5. Configurar Apache para que la raíz sea /var/www/html/public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 6. Copiar código y ejecutar instalación
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader

# 7. Permisos
RUN chown -R www-data:www-data /var/www/html