# syntax=docker/dockerfile:1

FROM messenger-web-app:latest

WORKDIR /var/www

USER root

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt update && apt install -y --no-install-recommends \
    libxslt1-dev \
    libzip-dev \
    curl && \
    install-php-extensions xdebug && \
    composer install && \
    npm install

COPY ./docker/messenger-dev.conf /etc/apache2/sites-available
COPY ./phpunit.xml.dist .
COPY ./tests tests

RUN a2dissite messenger && \
    a2dissite 000-default && \
    a2ensite messenger-dev

COPY ./docker/entrypoints/dev /usr/local/bin
RUN chmod 775 /usr/local/bin/dev

RUN find . -type f -exec chmod 664 {} + && \
find . -type d -exec chmod 775 {} + && \
find node_modules -exec chmod 744 {} +

EXPOSE 8181

ENTRYPOINT [ "/usr/local/bin/dev" ]

CMD [ "apachectl", "-D", "FOREGROUND" ]