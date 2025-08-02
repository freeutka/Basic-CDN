FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip zip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

RUN echo "PidFile /tmp/apache2.pid" >> /etc/apache2/apache2.conf
RUN mkdir -p /var/run/apache2 && chown www-data:www-data /var/run/apache2
