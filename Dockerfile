FROM php:8.1-fpm

# Update apt-get and install required packages
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer
RUN composer selfupdate
# Set working directory
WORKDIR /var/www/html
# Copy source code
COPY . .

# Set file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache 

# Install dependencies

RUN composer install --optimize-autoloader

# Set up cron job
#RUN echo "* * * * * www-data /usr/local/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1" | crontab -u www-data -

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]