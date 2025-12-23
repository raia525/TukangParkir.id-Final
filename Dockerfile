# ================================
# Laravel Production Dockerfile - Tailwind/Vite Support
# ================================
FROM php:8.2-apache

# ----------------
# 1. Install system packages & dependencies
# ----------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ----------------
# 2. Install Node.js LTS (20.x) dan npm
# ----------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ----------------
# 3. Install PHP extensions
# ----------------
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd

# ----------------
# 4. Enable Apache mod_rewrite
# ----------------
RUN a2enmod rewrite

# ----------------
# 5. Set Apache DocumentRoot ke /public
# ----------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# ----------------
# 6. Set working directory
# ----------------
WORKDIR /var/www/html

# ----------------
# 7. Install Composer
# ----------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------
# 8. Copy SELURUH Project Terlebih Dahulu
# ----------------
COPY . .

# ----------------
# 9. Install PHP dependencies
# ----------------
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction

# ----------------
# 10. Install Node dependencies & Build Assets
# ----------------
RUN if [ -f package.json ]; then \
    npm install && \
    npm run build; \
    fi

# ----------------
# 11. Set Laravel permissions
# ----------------
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# ----------------
# 12. Expose port 80 & Start Apache
# ----------------
EXPOSE 80
CMD ["apache2-foreground"]