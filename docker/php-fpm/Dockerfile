FROM exbox/php-fpm:4.0-beta

ADD ./www.conf /usr/local/etc/php-fpm.d/
ADD ./php.ini /usr/local/etc/php/php.ini
RUN usermod -u 1000 www-data

RUN addgroup --gid 3000 --system app
RUN adduser --uid 3000 --system --disabled-login --disabled-password --gid 3000 app

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000