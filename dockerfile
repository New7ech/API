# === 1) Étape de build (builder) ===
FROM php:8.3-fpm AS builder

# 1.1 Dépendances système pour PHP et Composer
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      git unzip curl libzip-dev libonig-dev libxml2-dev \
      libpng-dev libjpeg-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql zip bcmath gd intl \
 && pecl install redis && docker-php-ext-enable redis \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# 1.2 Copier tout le projet (inclut artisan, config, public, etc.)
COPY . .

# 1.3 Installer Composer puis dépendances PHP sans exécuter les scripts
RUN curl -sS https://getcomposer.org/installer \
      | php -- --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# === 2) Étape de production ===
FROM php:8.3-fpm

# 2.1 Extensions d’exécution
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      libpng-dev libjpeg-dev libfreetype6-dev \
      libzip-dev libicu-dev libxml2-dev libonig-dev \
      libpq-dev libcurl4-openssl-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql zip bcmath gd intl opcache \
 && pecl install redis && docker-php-ext-enable redis \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# 2.2 Copier code et extensions depuis builder
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer
COPY --from=builder /var/www /var/www
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# 2.3 Permissions et utilisateur
RUN chown -R www-data:www-data /var/www
USER www-data

EXPOSE 9000
CMD ["php-fpm"]
