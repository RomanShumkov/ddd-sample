#!/usr/bin/env bash

set -e

docker-compose build
docker-compose up -d
sleep 10
docker-compose exec composer /docker-entrypoint.sh composer install

cp .env.example .env

docker-compose exec artisan php /var/www/html/artisan doctrine:migrations:refresh
docker-compose exec artisan php /var/www/html/artisan migrate:refresh

echo "Navigate to http://127.0.0.1:8080/api/documentation to begin"
