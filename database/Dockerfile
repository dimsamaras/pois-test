FROM mysql:5.7

ADD ./config/db_schema.sql /docker-entrypoint-initdb.d

RUN chown -R mysql:root /var/lib/mysql/

CMD ["mysqld"]

EXPOSE 3306