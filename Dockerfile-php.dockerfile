FROM php:7.2.8-fpm
MAINTAINER ruzy
COPY ./configs /configs
RUN apt-get update &&\
	apt-get install -y --no-install-recommends\
        wget zip unzip vim sudo\
        libxml2\
        libzip-dev\
        libfreetype6-dev\
        libpng-dev\
        libjpeg62-turbo-dev\
        libxpm-dev\
        libvpx-dev\
        supervisor &&\
# install composer
	wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet &&\
	mv composer.phar /usr/local/bin/composer &&\
	useradd ruzy &&\
	usermod -u 1000 ruzy &&\
	mkdir /home/ruzy &&\
	chown 1000:1000 /home/ruzy &&\
# install required php extensions
	docker-php-ext-configure zip --with-libzip &&\
    docker-php-ext-configure gd\
        --with-freetype-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-vpx-dir=/usr/lib/x86_64-linux-gnu/ &&\
	docker-php-ext-install mbstring tokenizer ctype json zip pdo_mysql gd &&\
# allow ruzy use "sudo" command
	usermod -aG sudo ruzy &&\
# change user's password
	echo "ruzy:dd360870" | chpasswd &&\
# set environment file
	cp /configs/profile /etc/profile &&\
    #cp /configs/bash.bashrc /etc/bash.bashrc &&\
# clean cache
	rm -rf /configs &&\
	mkdir -p /var/cache/apt/archives/partial &&\
	apt-get autoclean &&\
	rm -r /var/cache/
# switch user to ruzy
USER 1000:1000
# install laravel
RUN composer global require "laravel/installer" &&\
    /bin/bash -c "source /etc/profile"
USER root
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
