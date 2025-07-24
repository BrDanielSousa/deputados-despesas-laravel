FROM php:8.2-fpm

WORKDIR /var/www/

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    cron \
    libzip-dev \
    && docker-php-ext-install pgsql pdo pdo_pgsql pdo_mysql zip gd

ADD . /var/www/

RUN chown -R www-data:www-data /var/www/

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions