FROM php:8.2-fpm

RUN apt-get update && apt-get install -y nginx gettext-base

RUN mkdir -p /home/container

COPY . /home/container

RUN chown -R www-data:www-data /home/container

RUN chmod +x /home/container/entrypoint.sh

EXPOSE 8080

CMD ["/home/container/entrypoint.sh"]
