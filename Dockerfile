FROM php:8.2-cli
RUN apt-get update && apt-get install -y libcurl4-openssl-dev && docker-php-ext-install curl

WORKDIR /var/www/html
COPY . /var/www/html

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]