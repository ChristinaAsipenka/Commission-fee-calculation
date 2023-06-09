FROM php:8.1-cli

WORKDIR "/app"

RUN apt-get update && apt-get install -y bash

RUN docker-php-ext-configure bcmath && docker-php-ext-install bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD [ "php", "-a" ]
