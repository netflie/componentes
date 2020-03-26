FROM php:7.2-fpm

# Set working directory
WORKDIR /var/www


RUN apt-get update && apt-get install -y \
    build-essential \
    zip \
    unzip \
    git

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY composer.json ./

RUN composer install

# RUN composer install

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
