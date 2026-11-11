FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends curl unzip git \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Сначала зависимости, чтобы слой кэшировался
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --no-progress --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --no-dev --no-interaction \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
                storage/logs bootstrap/cache database/sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database/sqlite

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
# Не "artisan serve": при наличии .env он сбрасывает переменные окружения контейнера
# и подхватывает значения из .env (например DB_DATABASE=plateshka). Встроенный сервер PHP их не трогает.
# Роутер Laravel (server.php) ищет index.php в текущей папке, поэтому запускаем из public/.
CMD ["sh", "-c", "cd public && exec php -S 0.0.0.0:8000 ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php"]
