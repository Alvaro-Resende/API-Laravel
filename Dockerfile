FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libpq-dev  \
    sqlite3 \
    libsqlite3-dev

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório da aplicação
WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala dependências PHP via Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ajusta permissões para o Laravel funcionar
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expõe a porta que o Laravel vai usar
EXPOSE 10000

# Comando para rodar o servidor no Render
CMD php artisan serve --host=0.0.0.0 --port=10000