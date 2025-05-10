FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install gd \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install exif \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Créer le répertoire de l'application
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Générer la clé d'application
RUN php artisan key:generate

# Créer le dossier de stockage et définir les permissions
RUN mkdir -p /var/www/storage && chmod -R 777 /var/www/storage

# Exposer le port
EXPOSE 8000

# Commande par défaut
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
