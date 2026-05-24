FROM php:8.4-apache

# Instalar extensiones necesarias para bases de datos
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para tu .htaccess
RUN a2enmod rewrite

# Copiar el código al contenedor
COPY . /var/www/html/

# Asegurar que el DocumentRoot apunte a public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf