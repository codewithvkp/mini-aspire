#!/bin/bash

if [ -e .env ]
then
    echo ".env exists"
else
    cp .env.example .env
    echo "Please update below variables in .env and press any key to continue,"
    echo
    echo "APP_NAME"
    echo "DB_HOST (Host of your database)"
    echo "DB_PORT (Port of your database)"
    echo "DB_DATABASE (Database name)"
    echo "DB_USERNAME (Database username)"
    echo "DB_PASSWORD (Password for database user)"
    echo
    read -r -n 1 -p ""
fi
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan passport:install
php artisan passport:client --personal --no-interaction
