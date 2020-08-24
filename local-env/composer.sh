#!/usr/bin/env bash

set -e

docker-compose exec composer /docker-entrypoint.sh composer "$@"
