#!/usr/bin/env bash

set -e

docker-compose exec artisan php /var/www/html/artisan doctrine:migrations:refresh
docker-compose exec artisan php /var/www/html/artisan migrate:refresh
