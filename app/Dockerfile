FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Instala a extensão MongoDB via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /var/www/html
