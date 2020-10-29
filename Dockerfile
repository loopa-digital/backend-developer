FROM php:7.4-fpm
RUN docker-php-ext-install bcmath pdo sockets

RUN apt-get update && apt-get install -y \
    git \
    zip

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www

RUN chmod -R 777 /var/www/storage

EXPOSE 9000

ENTRYPOINT [ "php-fpm" ]
