
FROM dwaskowski/php-nginx-percona
MAINTAINER Dmitrij Waskowski <dymitr@gmail.com>

RUN rm /etc/nginx/sites-available/default \
    && rm /etc/nginx/sites-enabled/default
COPY ./bootstarap/categories_api.conf /etc/nginx/conf.d/categories_api.conf

COPY ./bootstarap/bootstrap.sh /bootstrap.sh
RUN chmod +x /bootstrap.sh

COPY ./categories_api /srv/www/categories_api
RUN chown -R www-data:www-data /srv/www/categories_api

USER www-data
RUN cd /srv/www/categories_api \
    && composer install \
    && cd propel \
    && ../vendor/bin/propel sql:build \
    && ../vendor/bin/propel sql:insert

CMD ["/bootstrap.sh"]
