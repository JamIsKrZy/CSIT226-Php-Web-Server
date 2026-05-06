FROM php:8.2-apache

# Install system dependencies including MySQL client
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    mariadb-client-compat \
    && docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite (for routing)
RUN a2enmod rewrite

# Copy custom php config
COPY php.ini /usr/local/etc/php/

# Set working directory
WORKDIR /var/www/html

# Copy all application files
COPY . /var/www/html

# Set proper document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Create necessary directories with proper permissions
RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN cd /var/www/html && composer install --optimize-autoloader --no-interaction && \
    composer dump-autoload -o

EXPOSE 80
