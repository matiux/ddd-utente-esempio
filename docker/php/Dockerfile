FROM matiux/php:7.1-fpm-2.0.0

RUN echo 'alias test="vendor/bin/phpunit"' >> /home/utente/.bashrc && \
    echo 'alias sf="bin/console"' >> /home/utente/.bashrc  && \
    echo 'alias sfcc="rm -Rf var/cache/*"' >> /home/utente/.bashrc  && \
    echo 'alias memflush="echo \"flush_all\" | nc servicememcached 11211 -q 1"' >> /home/utente/.bashrc  && \
    /usr/local/bin/composer self-update && \
    mkdir -p /home/utente/.composer/cache && \
    chown -R utente:utente /home/utente/.composer/cache && \
    echo "ServerAliveInterval 120" >> /etc/ssh/ssh_config
