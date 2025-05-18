# Sử dụng hình ảnh PHP với Apache
FROM php:8.1-apache

# Cài đặt các phần mềm cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Cài mysqli và các thư viện khác nếu cần
RUN docker-php-ext-install mysqli

# Cài đặt PHP extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép mã nguồn vào container
COPY . /var/www/html/

# Chạy Composer để cài đặt các phụ thuộc
RUN composer install

# Thiết lập thư mục statics có thể truy cập từ Apache
RUN chown -R www-data:www-data /var/www/html/statics

# Cấu hình Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Mở port 80
EXPOSE 80

# Khởi động Apache
CMD ["apache2-foreground"]
