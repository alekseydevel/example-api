FROM php:7.0-fpm

MAINTAINER Oleksii Dubinin <oleksii.dubinin@gmail.com>

RUN apt-get update && apt-get install -y \
    git \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN docker-php-ext-install pdo pdo_mysql

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'alias ll="ls -la"' >> ~/.bashrc
RUN apt-get install nano

WORKDIR /app

#RUN chmod +x /app/console/command
