FROM php:8.4-apache

# 1. Instalar Composer (necesario para bajar las librerías)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Instalar extensiones necesarias para bases de datos
RUN docker-php-ext-install pdo pdo_mysql

# 3. Habilitar mod_rewrite
RUN a2enmod rewrite

# 4. Copiar los archivos de configuración de composer primero (para aprovechar caché)
COPY composer.json composer.lock* /var/www/html/

# 5. Instalar las dependencias
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# 6. Copiar el resto del código
COPY . .

# 7. Configurar Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf