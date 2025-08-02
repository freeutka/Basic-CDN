#!/bin/sh
export PORT=${PORT:-8080}

envsubst '${PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

php-fpm &
nginx -g 'daemon off;'
