FROM php:8.2-fpm

ARG user=daniel
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    cron 

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd sockets

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install Redis & Xdebug
RUN pecl install redis && docker-php-ext-enable redis
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Set working directory
WORKDIR /var/www

# Copy project files
COPY ./backend .

COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy and configure cron
COPY ./docker/crontab /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron
RUN touch /var/log/cron.log

# Change ownership of files
RUN chown -R $user:$user /var/www

# root to run cron
USER root

# Copy and permissions to start.sh
COPY ./backend/start.sh /var/www/start.sh
RUN chmod +x /var/www/start.sh

# CMD to start services
CMD ["/var/www/start.sh"]
