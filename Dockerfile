# Use an official PHP image as the base image
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    cron \
    && docker-php-ext-install pdo pdo_mysql

# Install PostgreSQL extension
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
# Copy the Laravel application files into the container
COPY . /var/www
# Set working directory
WORKDIR /var/www
# Install Composer to manage PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
#RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -
#RUN apt-get install -y nodejs
## Install npm (sometimes it's not installed automatically with Node.js)
#RUN apt-get install -y npm
# Copy custom configurations PHP
COPY dev/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
USER root
COPY ./dev/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./dev/cron /etc/cron.d/self-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/self-cron

# Apply cron job
RUN crontab /etc/cron.d/self-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# RUN mkdir -p storage/framework/cache/data storage/app storage/framework/sessions storage/framework/views
# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
RUN usermod -a -G www root
RUN usermod -a -G www www-data


ADD . /var/www
RUN chown -Rf www-data:www-data /var/www
# Change current user to www

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
#CMD ["php-fpm"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
