FROM php:7.4-apache

# Mag-install ng mga kinakailangang extensions para sa Laravel at Database (MySQL/PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd bcmath

# I-install ang Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Itakda ang working directory
WORKDIR /var/www/html

# I-copy ang buong project files sa container
COPY . .

# I-set ang tamang permissions para sa storage at bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Baguhin ang Apache DocumentRoot para tumuro sa 'public' folder ng Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# I-enable ang Apache Rewrite module para sa Laravel routes
RUN a2enmod rewrite

# I-install ang mga PHP dependencies gamit ang Composer
RUN composer install --no-dev --optimize-autoloader

# Buksan ang port 80 para sa web traffic
EXPOSE 80

# Command para patakbuhin ang Apache server
CMD ["apache2-foreground"]