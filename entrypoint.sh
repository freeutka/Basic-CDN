#!/bin/sh
export PORT=${PORT:-8080}
envsubst '${PORT}' < /home/container/default.conf.template > /home/container/default.conf
php-fpm &
nginx -g 'daemon off;' -c /home/container/default.conf
