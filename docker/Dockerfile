FROM php:8.1.0-fpm

RUN apt-get update

#Installing git
RUN apt-get install -y git curl


#***************************
#***INTERNAL EXTENSIONS*****
#***************************

#Installing bzip2 extension
RUN apt-get install -y libbz2-dev
RUN docker-php-ext-install -j$(nproc) bz2

#Installing pdo_mysql extension
RUN docker-php-ext-install -j$(nproc) pdo_mysql

#Installing zip extension
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install -j$(nproc) zip

#Installing xsl extension
RUN apt-get install -y libxslt1-dev
RUN docker-php-ext-install -j$(nproc) xsl

#Installing BCMath extension
RUN docker-php-ext-install -j$(nproc) bcmath

#Installing Intl extension
RUN docker-php-ext-install -j$(nproc) intl

#Installing Sockets extension
RUN docker-php-ext-install -j$(nproc) sockets

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

#***************************
#*****PECL EXTENSIONS*******
#***************************

#Installing composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer


#Default cd to app dir
WORKDIR /app
