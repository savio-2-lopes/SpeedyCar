FROM php:8.1-cli
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install git zip unzip zlib1g-dev libzip-dev nano -y && apt-get upgrade -y 
RUN docker-php-ext-install zip
RUN pecl install swoole && docker-php-ext-enable swoole
WORKDIR /var/www
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chown -R www-data:www-data /var/www && chmod -R 777 /var/www/ 
COPY app/ /var/www
ENTRYPOINT ["php", "/var/www/index.php", "start"]