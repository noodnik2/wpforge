# FROM wordpress:6.7.2-php8.4-apache
FROM php:8.2-apache

ARG WP_DIR="backups/duplicator"

# Install required PHP extensions, system dependencies and desired toolset
RUN apt-get update && apt-get install -y unzip zip libzip-dev vim \
    && docker-php-ext-install zip mysqli \
    && apt-get clean

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Clean target folder
RUN rm -rf /var/www/html/*

# Copy the specified WordPress files into the image
RUN echo "Copying from WP_DIR=${WP_DIR}"
COPY ${WP_DIR}/. /var/www/html

