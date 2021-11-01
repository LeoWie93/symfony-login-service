FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        locales \
        cron \
        git \
        php7.4-bz2 \
        php7.4-mysql \ 
        php7.4-xdebug \ 
        php7.4-yaml; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN usermod -u 1000 www-data

COPY crontab /etc/cron.d/crontab-task
RUN chmod 0644 /etc/cron.d/crontab-task
RUN crontab /etc/cron.d/crontab-task
RUN touch /var/log/cron.log

RUN echo "de_CH UTF-8" > /etc/locale.gen
RUN locale-gen de_CH.UTF-8 && export LC_ALL=de_CH.UTF-8

# Copy the start script.
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod 0777 /usr/local/bin/docker-entrypoint.sh
# Set the entrypoint.
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm7.4"]
