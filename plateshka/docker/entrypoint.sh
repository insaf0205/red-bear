#!/bin/sh
set -e

# SQLite-файл лежит в volume, при первом запуске создаём его
touch "$DB_DATABASE"

php artisan migrate --force

exec "$@"
