FROM devalliance/nginx-php-fpm:7.2

# Configure nginx
COPY nginx-php-fpm/nginx/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY nginx-php-fpm/php/fpm-pool.conf /etc/php7/php-fpm.d/zzz_custom.conf
COPY nginx-php-fpm/php/php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY nginx-php-fpm/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Crea la carpeta en www
RUN mkdir -p /var/www/html
WORKDIR /var/www/html
COPY . /var/www/html

RUN chmod 777 /var/www/html/storage -R
RUN chmod 777 /var/www/html/bootstrap -R
RUN composer install --ignore-platform-reqs

# Modifica todo los permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
