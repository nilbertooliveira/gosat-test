FROM php:8-fpm-alpine
#FROM nilbertooliveira/php:8-fpm-alpinedock

USER root

# Copy composer.lock and composer.json
#COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apk add --update linux-headers
RUN apk add --no-cache $PHPIZE_DEPS  \
    freetype  \
    libpng  \
    libjpeg-turbo  \
    freetype-dev  \
    libpng-dev  \
    libjpeg-turbo-dev \
    oniguruma-dev  \
    libzip-dev  \
    libxml2-dev  \
    icu-dev \
    libpq-dev \
    nodejs \
    npm

RUN pecl install xdebug redis && docker-php-ext-enable xdebug redis

# Install extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl soap
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/
RUN docker-php-ext-install gd

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer #--version=1.10.26

# Configurações do supervisor
RUN touch /var/run/supervisor.sock
RUN chmod 777 /var/run/supervisor.sock

RUN mkdir -p "/etc/supervisor/logs"
RUN chmod -R 777 "/etc/supervisor/logs"
ADD ./docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
ADD ./docker/supervisor/horizon.conf /etc/supervisor/conf.d/horizon.conf
ADD ./docker/supervisor/echoserver.conf /etc/supervisor/conf.d/echoserver.conf

# Create a group and user
RUN addgroup -g 1000 www
RUN adduser -u 1000 -s /bin/bash --disabled-password -G www www
RUN chmod -R 775 "/usr/local/bin/composer"

# Copy existing Application directory contents
COPY . /var/www
RUN composer install

# Copy existing Application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
