#!/usr/bin/env bash

/etc/init.d/php7.2-fpm start && /etc/init.d/php7.2-fpm status
/etc/init.d/nginx start && /etc/init.d/nginx status
chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
/etc/init.d/mysql restart && /etc/init.d/mysql status
/bin/bash
