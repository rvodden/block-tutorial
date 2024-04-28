FROM php:8.2-apache

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
# Enable .htaccess files & mod_rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride all/' /etc/apache2/apache2.conf
RUN a2enmod rewrite

#Most of this comes directly from the WordPress docker image
#We don't inherit from it becase it has a VOLUME that causes issues
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends ghostscript git less ssh-client mariadb-client sudo; \
	rm -rf /var/lib/apt/lists/*

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash && . ~/.bashrc && nvm install --lts && node -v && npm -v

# install the PHP extensions, including XDebug
RUN set -ex; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		libfreetype6-dev \
		libjpeg-dev \
		libmagickwand-dev \
		libpng-dev \
		libzip-dev \
	; \
	docker-php-ext-configure gd --with-freetype --with-jpeg; \
	docker-php-ext-install -j "$(nproc)" \
		bcmath \
		exif \
		gd \
		mysqli \
		opcache \
		zip \
	; \
	pecl install imagick-3.7.0; \
  	pecl install xdebug-3.3.2; \
	docker-php-ext-enable imagick xdebug;\
	rm -rf /var/lib/apt/lists/*

#XDebug settings
RUN echo "[XDebug]\nxdebug.mode = debug\n" > $PHP_INI_DIR/conf.d/xdebug.ini

#WP recommended PHP settings
RUN { \
	echo 'opcache.memory_consumption=128'; \
	echo 'opcache.interned_strings_buffer=8'; \
	echo 'opcache.max_accelerated_files=4000'; \
	echo 'opcache.revalidate_freq=2'; \
	echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

#install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

#install Composer
WORKDIR /tmp
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
		&& php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer

ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=$USER_UID

# Create the user
RUN groupadd --gid $USER_GID $USERNAME \
	&& useradd --uid $USER_UID --gid $USER_GID -m $USERNAME -s /usr/bin/bash \
	&& usermod -aG www-data $USERNAME \
	#
	# [Optional] Add sudo support. Omit if you don't need to install software after connecting.
	&& apt-get update \
	&& apt-get install -y sudo \
	&& echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME \
	&& chmod 0440 /etc/sudoers.d/$USERNAME

WORKDIR /var/www/html
USER www-data

#Download WP
RUN wp core download

#add dirs for plugin and theme dev
RUN mkdir -p /var/www/html/wp-content/plugins/plugin-dev; \
	mkdir -p /var/www/html/wp-content/themes/theme-dev;

USER root
RUN chown -R www-data:www-data /var/www/html/
RUN chmod g+w -R /var/www/html/
RUN find /var/www/html/ -type d -exec chmod g+s {} \;

USER vscode
