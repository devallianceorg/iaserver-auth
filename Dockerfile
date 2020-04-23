FROM devalliance/nginx-php-fpm:7.1

# Configure nginx
COPY nginx-php-fpm/nginx/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY nginx-php-fpm/php/fpm-pool.conf /etc/php7/php-fpm.d/zzz_custom.conf
COPY nginx-php-fpm/php/php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY nginx-php-fpm/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Add application
RUN mkdir -p /var/www/html
WORKDIR /var/www/html
COPY . /var/www/html/

RUN chmod 777 /var/www/html/storage -R
RUN composer install

EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
