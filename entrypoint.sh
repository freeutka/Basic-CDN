#!/bin/bash

PORT=${SERVER_PORT:-8080}

envsubst '$PORT' < /home/container/default.conf.template > /home/container/default.conf

nginx -c /home/container/default.conf -g "daemon off;"
