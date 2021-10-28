
FROM cylab/php74 AS composer

COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader


FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis


RUN sed -i -e "s/html/html\/public/g" \
    /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite


COPY . /var/www/html

COPY env.docker /var/www/html/.env
COPY --from=composer /var/www/html/vendor /var/www/html/vendor

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && php artisan config:clear