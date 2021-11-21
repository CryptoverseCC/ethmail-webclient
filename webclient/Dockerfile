FROM roundcube/roundcubemail:1.5-rc-apache

RUN apt-get update && apt-get install -y \
    patch \
    && rm -rf /var/lib/apt/lists/*

RUN set -ex; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
    git \
    ; \
    \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer; \
    composer \
    --working-dir=/usr/src/roundcubemail/ \
    --no-interaction \
    config \
    secure-http true \
    ; \
    composer \
    --working-dir=/usr/src/roundcubemail/ \
    --prefer-dist --no-dev \
    --no-interaction \
    update;

RUN composer \
    --working-dir=/usr/src/roundcubemail/ \
    --prefer-source \
    --no-interaction \
    require \
    "roundcube/plugin-installer:0.3.0"

COPY plugin /plugin
COPY config/ /var/roundcube/config/
COPY skins/ethmail /usr/src/roundcubemail/skins/ethmail

RUN composer \
    --working-dir=/usr/src/roundcubemail/ \
    --no-interaction \
    config \
    repositories.local path /plugin \
    ; \
    composer \
    --working-dir=/usr/src/roundcubemail/ \
    --prefer-source \
    --no-interaction \
    require \
    cryptoverse/ethmail_webclient;
