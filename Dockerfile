# Set master image
FROM php:7.4-fpm-alpine

# Lumen will not be able to connect with it without the pdo_mysql extension
RUN docker-php-ext-install pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# We can also use the following command to install composer
# RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Copy existing application directory into the container
COPY . .

# Install dependencies
RUN composer install

# Start php server: no need, we will start it in docker-compose.yml
# ENTRYPOINT ["php", "-S", "app:8888", "-t", "public"]
