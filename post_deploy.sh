#!/bin/sh

# update application cache
php artisan optimize

# run only migrations
# php artisan migrate --force

# comment out the next line and enable above if you don't want to seed the database'
php artisan migrate:fresh --seed --force

# start the servers
# nginx && php-fpm
php-fpm -D &&  nginx -g "daemon off;"


