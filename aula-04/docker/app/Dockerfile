FROM ambientum/php:7.3-nginx

USER root

RUN apk upgrade --update && apk add \
    curl \
    nano \
    bash \
    shadow \
    libtool \
    zlib-dev \
    exiftool \
    libpng-dev

USER ambientum

WORKDIR /var/www/app


