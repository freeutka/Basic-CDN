#!/bin/sh
export PORT=${PORT:-8080}

/usr/sbin/php-fpm -D
nginx -g "daemon off;"
