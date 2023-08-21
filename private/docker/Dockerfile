FROM debian:bookworm

RUN apt-get update && apt-get install -y acl curl vim rsyslog postgresql-all redis-server nginx php-fpm php-xml php-curl php-xmlrpc php-sqlite3 php-mbstring php-pgsql php-ast php-redis composer && rm -rf /var/lib/apt/lists/*

RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default
COPY vhost /etc/nginx/sites-available/php
RUN ln -s /etc/nginx/sites-available/php /etc/nginx/sites-enabled/php
COPY php.ini /etc/php/8.2/fpm/php.ini
COPY locale.gen /etc/locale.gen
RUN /usr/sbin/locale-gen
COPY pg_hba.conf /etc/postgresql/15/main/pg_hba.conf
COPY postgresql.conf /etc/postgresql/15/main/postgresql.conf
COPY rsyslog.conf /etc/rsyslog.conf
COPY docker_go /bin/docker_go

RUN chmod a+x /bin/docker_go

CMD [ "/bin/docker_go" ]
