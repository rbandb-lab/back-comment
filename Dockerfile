ARG PHP_VERSION=8.2
ARG CADDY_VERSION=2

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine AS symfony_php

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	; \
\
RUN set -eux; \
	install-php-extensions \
		intl \
		zip \
		apcu \
		opcache \
	; \
	install-php-extensions pdo_pgsql

COPY .docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY .docker/php/conf.d/symfony.prod.ini $PHP_INI_DIR/conf.d/symfony.ini

COPY .docker/php/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY .docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint; \
    mkdir -p /srv/app/var/cache /srv/app/var/log

VOLUME /var/run/php

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/srv/.composer

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/app

# Download the Symfony skeleton and leverage Docker cache layers
RUN composer install --prefer-dist --no-dev --no-progress --no-interaction; \
	composer clear-cache

COPY . .

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer symfony:dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync

VOLUME /srv/app/var
VOLUME /var/run/php

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM caddy:${CADDY_VERSION}-builder-alpine AS symfony_caddy_builder

RUN xcaddy build

FROM caddy:${CADDY_VERSION} AS symfony_caddy

WORKDIR /srv/app

COPY --from=symfony_caddy_builder /usr/bin/caddy /usr/bin/caddy
COPY --from=symfony_php /srv/app/public public/
COPY .docker/caddy/Caddyfile /etc/caddy/Caddyfile
