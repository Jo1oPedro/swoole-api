FROM php:8.2-cli

RUN apt update \
    && apt install -y libpq-dev \
                      zip \
                      unzip \
                      p7zip-full \
                      telnet \
                      curl

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN set -eux;

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs

# Install npm
RUN apt-get install -y npm

# Install nodemon globally
RUN npm install -g nodemon

# instalando extensão mysql para pdo
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install sockets
# Installing extension for Memcached
RUN apt install -y libz-dev libmemcached-dev && pecl install memcached && docker-php-ext-enable memcached

RUN pecl install swoole
RUN docker-php-ext-enable swoole

ENV COMPOSER_ALLOW_SUPERUSER=1

# baixar o composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.* ./

#RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

COPY framework /var/www/html/framework
WORKDIR /var/www/html/framework
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

WORKDIR /var/www/html
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

#COPY public /var/www/html/public
#COPY src /var/www/html/src
#COPY config /var/www/html/config
#COPY enviroment /var/www/html/enviroment
#COPY Migrations /var/www/html/migrations
#COPY migrations.php /var/www/html/migrations.php
#COPY migrations-db.php /var/www/html/migrations-db.php

RUN composer dump-autoload --optimize

#COPY custom-php.ini /usr/local/etc/php/conf.d/

# Enable error reporting and display errors
#RUN echo "display_errors=On" >> /usr/local/etc/php/php.ini
#RUN echo "error_reporting=E_ALL" >> /usr/local/etc/php/php.ini

#CMD ["php", "/var/www/html/public/server.php"]
#CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html/public", "/var/www/html/public/index.php"]
#CMD ["nodemon -L", "/var/www/html/public/server.php"]
