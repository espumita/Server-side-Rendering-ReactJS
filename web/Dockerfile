FROM php:7.1.0-apache

MAINTAINER davidfalconnaranjo@gmail.com

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '61069fe8c6436a4468d0371454cf38a812e451a14ab1691543f25a9627b97ff96d8753d92a00654c21e2212a5ae1ff36') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --version=1.3.0 --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY src/ /var/www/html/
COPY composer.json /var/www/html/composer.json

WORKDIR /var/www/html/

RUN composer install --no-interaction \
    && composer update