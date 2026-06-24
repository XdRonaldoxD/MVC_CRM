FROM php:7.4-apache

# Extensiones PHP requeridas por el proyecto
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        gd \
        zip \
        intl \
        exif \
        bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite para las rutas del MVC
RUN a2enmod rewrite headers

# Configuracion de PHP (limites para uploads y PDFs)
RUN echo "upload_max_filesize = 128M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size = 128M"        >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time = 300"    >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_input_time = 300"        >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit = 256M"         >> /usr/local/etc/php/conf.d/uploads.ini

# Permitir .htaccess en el directorio raiz
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Configuracion de Apache para servir frontend + backend
COPY apache-crm.conf /etc/apache2/sites-available/crm.conf
RUN a2dissite 000-default && a2ensite crm

# Copiar el proyecto
WORKDIR /var/www/html
COPY . .

# Permisos de escritura para carpetas que necesita la app
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html \
 && chmod -R 775 /var/www/html/archivo \
 && mkdir -p /var/www/html/frontend/api/archivo \
 && chmod -R 775 /var/www/html/frontend/api/archivo

EXPOSE 80
