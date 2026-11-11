#!/bin/sh
set -e

# Инициализация storage, если пустой
if [ ! "$(ls -A /var/www/storage)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/storage-init/. /var/www/storage
  chown -R www-data:www-data /var/www/storage
fi
rm -rf /var/www/storage-init

# --- ДОБАВЬТЕ ЭТОТ БЛОК ---
# Так как папка /var/www/public перекрыта пустым volume при первом старте,
# принудительно восстановим index.php и статику, если их там нет
if [ ! -f "/var/www/public/index.php" ]; then
  echo "Restoring public directory assets..."
  # Файлы физически запечены в образе, копируем их в наш volume
  cp -R /var/www/html/public/. /var/www/public 2>/dev/null || cp -R /var/www/public_bak/. /var/www/public 2>/dev/null || true
fi
# --------------------------

# Выполнение миграций
php artisan migrate --force

# Кэширование для продакшена
php artisan config:cache
php artisan route:cache

# Запуск основного процесса (php-fpm)
exec "$@"
