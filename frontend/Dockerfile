FROM php:8.1-apache
RUN apt-get update && apt-get install -y git zip unzip zlib1g-dev libzip-dev nano libonig-dev libzip-dev unzip && docker-php-ext-install mbstring zip
RUN docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN service apache2 restart
