# Use the official PHP image with Apache
FROM php:7.2-apache

WORKDIR /var/www/html


RUN apt-get update && apt-get install -y \
    git \
    unzip 

    
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

