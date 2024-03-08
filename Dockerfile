ARG VERSION="8.3"

# Install frontend dependencies and build JS and CSS
FROM node:alpine as npm

WORKDIR /app

COPY ./package.json /app/
COPY ./package-lock.json /app/

RUN npm install

COPY ./webpack.config.js /app/
COPY ./assets /app/assets/

RUN mkdir -p /app/public/build && npm run build

FROM php:${VERSION}-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the code into the container
COPY . .

# Copy assets
COPY --from=npm /app/public/build/ /var/www/html/public/build/

## Set default php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

## Copy custom configurations
COPY ./deployment/apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./deployment/php.ini /usr/local/etc/php/conf.d/custom-php.ini

# Install PHP extensions and other dependencies
RUN apt-get update && \
    apt-get install -y wget git libpq-dev libicu-dev libzip-dev && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install intl opcache pgsql pdo pdo_pgsql zip && \
    rm -rf /var/lib/apt/lists/* && \
    a2enmod rewrite

## Install composer
RUN wget https://getcomposer.org/installer && \
    php installer --install-dir=/usr/local/bin/ --filename=composer && \
    rm installer

## Setting env
ENV APP_ENV=prod
ENV COMPOSER_ALLOW_SUPERUSER=1

## Install application dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

## Cleanup
RUN composer dump-autoload --no-dev --classmap-authoritative && \
    rm /usr/local/bin/composer

## Install assets
RUN php bin/console assets:install

## Change files owner to apache default user
RUN chown -R www-data:www-data /var/www/html

# Expose the port Apache listens on
EXPOSE 80