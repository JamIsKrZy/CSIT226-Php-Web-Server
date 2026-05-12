FROM php:8.2-apache

# ============================================================
# LAYER 1: System dependencies
# Cached until apt packages or PHP extensions change
# ============================================================
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    mariadb-client-compat \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ============================================================
# LAYER 2: Apache + PHP config
# Cached until configs change
# ============================================================
RUN a2enmod rewrite

COPY php.ini /usr/local/etc/php/

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# ============================================================
# LAYER 3: Composer binary
# Cached until this RUN instruction changes
# ============================================================
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

# ============================================================
# LAYER 4: PHP dependencies (the critical caching layer)
# ONLY copy composer.json and composer.lock first.
# This layer is re-used as long as those two files don't change,
# even if your app source code changes.
# ============================================================
WORKDIR /var/www/html

COPY composer.json  ./

RUN composer install \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --prefer-dist

# ============================================================
# LAYER 5: Application source code
# This is the only layer that rebuilds on normal code changes
# ============================================================
COPY . /var/www/html

# Re-run dump-autoload now that actual source is present
RUN composer dump-autoload -o

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80