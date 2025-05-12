FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    python3 \
    python3-pip \
    python3-venv \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libmcrypt-dev \
    libpq-dev \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip
RUN python3 -m venv /opt/awscli-venv && \
    /opt/awscli-venv/bin/pip install --upgrade pip awscli && \
    ln -s /opt/awscli-venv/bin/aws /usr/local/bin/aws
RUN pecl install redis && docker-php-ext-enable redis
COPY php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache
EXPOSE 9000
CMD ["php-fpm"]
