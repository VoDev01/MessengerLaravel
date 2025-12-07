# syntax=docker/dockerfile:1

FROM messenger-web-app:latest

WORKDIR /var/www

USER root

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt update && apt install -y --no-install-recommends \
    apache2-dev \
    curl \
    unzip \
    coreutils \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev  && \
    install-php-extensions xdebug && \
    composer install && \
    npm install

COPY ./docker/messenger-dev.conf /etc/apache2/sites-available

RUN a2dissite messenger && \
    a2dissite 000-default && \
    a2ensite messenger-dev

COPY ./docker/entrypoints/dev.sh /usr/local/bin
RUN chmod 775 /usr/local/bin/dev.sh

RUN find node_modules -exec chmod 744 {} +

EXPOSE 8181

ENTRYPOINT [ "/usr/local/bin/dev.sh" ]

CMD [ "apachectl", "-D", "FOREGROUND" ]