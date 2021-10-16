FROM php:8-fpm

USER root

WORKDIR /var/www

  # Install dependencies
RUN apt-get update \
  # gd
&& apt-get install -y --no-install-recommends build-essential  openssl nginx libfreetype6-dev zlib1g-dev libzip-dev gcc g++ make vim unzip curl git libonig-dev  \
# && docker-php-ext-configure gd  \
# && docker-php-ext-install gd \
  # pdo_mysql
&& docker-php-ext-install pdo_mysql mbstring \
  # pdo
&& docker-php-ext-install pdo \
  # opcache
&& docker-php-ext-enable opcache \
  # zip
&& docker-php-ext-install zip \
&& apt-get autoclean -y \
&& rm -rf /var/lib/apt/lists/* \
&& rm -rf /tmp/pear/

  # Copy files
COPY . /var/www

COPY ./deploy/local.ini /usr/local/etc/php/local.ini

COPY ./deploy/nginx.conf /etc/nginx/nginx.conf

RUN chmod +rwx /var/www

RUN chmod -R 777 /var/www

  # setup composer and laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --working-dir="/var/www"

RUN composer dump-autoload --working-dir="/var/www"

EXPOSE 80

RUN ["chmod", "+x", "post_deploy.sh"]

CMD [ "sh", "./post_deploy.sh" ]

