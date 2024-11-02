FROM php:8.3.10-fpm

RUN echo "deb http://ftp.de.debian.org/debian/ bookworm main" > /etc/apt/sources.list
# Install PDO extensions
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mysqli

# Install zip extension for PHP
RUN apt-get install -y libzip-dev zip unzip
RUN docker-php-ext-install zip
RUN apt-get update && \
    apt-get install -y libcups2

# Install Composer and Git
RUN apt-get update && apt-get install -y curl git
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm composer-setup.php
RUN apt-get install -y pdftk-java

ENV PATH=$PATH:/usr/local/bin

# install required libs for health check
RUN apt-get -y install libfcgi0ldbl nano htop iotop lsof cron mariadb-client redis-tools wget

# Install Sodium extension for better PASETO v4 performance
RUN apt-get update && apt-get install -y --no-install-recommends \
    libsodium-dev \
    && docker-php-ext-install sodium

RUN apt-get update && apt-get install -y --no-install-recommends \
    libgmp-dev \
    && docker-php-ext-install gmp sodium


RUN apt-get -y install gcc make autoconf libc-dev pkg-config libzip-dev

RUN apt-get install -y --no-install-recommends \
	git \
	libz-dev \
	libpq-dev \
	libxml2-dev \
	libmemcached-dev \
	libldap2-dev libbz2-dev \
	zlib1g-dev libicu-dev g++ \
	libssl-dev libssl-doc libsasl2-dev \
	curl libcurl4-openssl-dev

RUN apt-get install -y --no-install-recommends \
	libgmp-dev firebird-dev libib-util

RUN apt-get install -y --no-install-recommends \
	re2c libpng++-dev libwebp-dev libjpeg-dev libjpeg62-turbo-dev libpng-dev libxpm-dev libvpx-dev libfreetype6-dev

RUN apt-get install -y --no-install-recommends \
	python3-lib2to3 libmagick++-dev libmagickwand-dev

RUN apt-get install -y --no-install-recommends \
	zlib1g-dev libgd-dev \
	unzip libpcre3 libpcre3-dev \
	sqlite3 libsqlite3-dev libxslt-dev \
	libtidy-dev libxslt1-dev libmagic-dev libexif-dev file \
	libmhash2 libmhash-dev libc-client-dev libkrb5-dev libssh2-1-dev \
	poppler-utils ghostscript libmagickwand-6.q16-dev libsnmp-dev libedit-dev libreadline6-dev libsodium-dev \
	freetds-bin freetds-dev freetds-common libct4 libsybdb5 tdsodbc libreadline-dev librecode-dev libpspell-dev libonig-dev

# issue on linux/amd64
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

# fix for docker-php-ext-install pdo_dblib
# https://stackoverflow.com/questions/43617752/docker-php-and-freetds-cannot-find-freetds-in-know-installation-directories
RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/
RUN docker-php-ext-install pdo_dblib

# install GD
RUN docker-php-ext-configure gd --with-jpeg --with-xpm --with-webp --with-freetype && \
	docker-php-ext-install -j$(nproc) gd

RUN pecl install redis

RUN docker-php-ext-enable redis.so

ADD configs/php.ini /usr/local/etc/php/
ADD configs/www.conf /usr/local/etc/php-fpm.d/


RUN echo '#!/bin/bash' > /healthcheck && \
	echo 'env -i SCRIPT_NAME=/health SCRIPT_FILENAME=/health REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1' >> /healthcheck && \
	chmod +x /healthcheck
# COPY ./php-fpm.conf /usr/local/etc/php-fpm.d/www.conf



# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]