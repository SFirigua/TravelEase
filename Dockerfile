# Usar una imagen base con PHP y Apache
FROM php:8.2-apache

# Copiar los archivos de tu proyecto al directorio raíz del servidor web
COPY . /var/www/html/

# Establecer los permisos para que Apache pueda acceder a los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilitar el módulo de reescritura de Apache (útil si usas rutas amigables)
RUN a2enmod rewrite

# Exponer el puerto 80 para HTTP
EXPOSE 80
