FROM php:8.2-fpm

# Cài đặt các extension cần thiết
RUN apt-get update && apt-get install -y \
    libpq-dev \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cấu hình Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy source code
COPY . /var/www/html
WORKDIR /var/www/html

# Cài đặt dependencies
RUN composer install

# Cấu hình quyền truy cập
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80

# Start services
CMD service nginx start && php-fpm 