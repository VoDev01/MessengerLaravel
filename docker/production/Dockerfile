#syntax=docker/dockerfile:1

FROM php:8.3-apache AS build

WORKDIR /var/www

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt update && apt install -y \
    npm \
    mariadb-client && \
    install-php-extensions @composer \
    calendar \
    bcmath \
    ffi \
    gd \
    gettext \
    igbinary \
    imagick \
    imap \
    intl \
    msgpack \
    mysqli \
    pcntl \
    pdo_mysql \
    shmop \
    soap \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    xmlrpc \
    xsl \
    zip \
    opcache

COPY . /var/www

ARG UNAME=vodev
ARG UID=1000
RUN useradd -m -u $UID -g www-data -o -s /bin/bash $UNAME

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist && \
    npm install --dev && \
    npm run build && \
    chown ${UNAME}:www-data -R . && \
    find . -type f -exec chmod 640 {} + && \
    find . -type d -exec chmod 750 {} + && \
    find storage -type d -exec chmod 775 {} + && \
    find storage/* -type f -exec chmod 664 {} + && \
    find bootstrap -type d -exec chmod 775 {} + && \
    find bootstrap/* -type f -exec chmod 664 {} + && \
    find public -type d -exec chmod 775 {} + && \
    find public/* -type f -exec chmod 664 {} +

FROM build AS apache2

WORKDIR /var/www

COPY ./docker/messenger.conf /etc/apache2/sites-available

RUN a2enmod ssl && \
    a2enmod rewrite && \
    a2dissite 000-default && \
    a2ensite messenger

COPY ./docker/entrypoints/prod.sh /usr/local/bin

RUN chmod 775 /usr/local/bin/prod.sh && \
mkdir /etc/apache2/cert && \
chown www-data:www-data /etc/apache2/cert && \
chmod 750 /etc/apache2/cert

USER www-data

EXPOSE 4433

ENTRYPOINT ["/usr/local/bin/prod.sh"]

CMD ["apachectl", "-D", "FOREGROUND"]