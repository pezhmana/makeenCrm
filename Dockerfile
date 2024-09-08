FROM php:8.2-fpm-alpine
RUN docker-php-ext-install exif
RUN apk update && apk add --no-cache \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
     libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
COPY . .
RUN composer install
RUN addgroup app && adduser -S -G app app
RUN mkdir data
RUN chown -R app:app /app/storage /app/bootstrap/cache /app/data/
RUN chmod -R 755 /app/storage /app/bootstrap/cache /app/data/
USER app
EXPOSE 8000
CMD ["php","artisan","serve","--host","0.0.0.0"]
