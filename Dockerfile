#Usa PHP 8.2 con Apache
FROM php:8.2-apache

#Instala extensiones necesarias (MongoDB, PDO)
RUN apt-get update && apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql

#Habilita mod_rewrite para Apache (si usas rutas amigables)
RUN a2enmod rewrite

#Copia el archivo de configuración de Apache
#COPY apache.conf /etc/apache2/sites-available/000-default.conf

#Copia el código de la aplicación al contenedor
COPY . /var/www/html/

#Otorga permisos correctos
RUN chown -R www-data:www-data /var/www/html

#Establece la raíz del servidor en public/
WORKDIR /var/www/html/public

#Expone el puerto 80
EXPOSE 80

#Comando para iniciar Apache
CMD ["apache2-foreground"]