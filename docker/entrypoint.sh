#!/bin/bash

echo "Waiting for database..."
while ! nc -z db 3306; do
  sleep 1
done
echo "Database is ready!"

cp .env.docker .env

php artisan key:generate --force

php artisan storage:link

php artisan migrate:fresh --seed --force

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
