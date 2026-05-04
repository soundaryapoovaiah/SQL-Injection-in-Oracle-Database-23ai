FROM handsonsecurity/seed-server:apache-php

ARG WWWDir=/var/www/SQL_Injection

COPY Code $WWWDir
COPY apache_sql_injection.conf  /etc/apache2/sites-available
RUN  a2ensite apache_sql_injection.conf 


# ---- Oracle Instant Client 23 + OCI8 ----
# (Using local zip files already placed in image_www/)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libaio1 unzip build-essential php-pear php-dev \
 && rm -rf /var/lib/apt/lists/*

# Copy the Instant Client zips into the image and unzip
COPY instantclient-basiclite-linux.x64-23.26.1.0.0.zip /tmp/
COPY instantclient-sdk-linux.x64-23.26.1.0.0.zip /tmp/

RUN rm -rf /opt/oracle && mkdir -p /opt/oracle \
 && cd /opt/oracle \
 && unzip -oq /tmp/instantclient-basiclite-linux.x64-23.26.1.0.0.zip \
 && unzip -oq /tmp/instantclient-sdk-linux.x64-23.26.1.0.0.zip \
 && rm -f /tmp/instantclient-*.zip \
 && ln -s /opt/oracle/instantclient_* /opt/oracle/instantclient \
 && echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf \
 && ldconfig \
 && pecl channel-update pecl.php.net \
 && printf "instantclient,/opt/oracle/instantclient\n" | pecl install oci8-2.2.0 \
 && echo "extension=oci8.so" > /etc/php/7.4/cli/conf.d/20-oci8.ini \
 && echo "extension=oci8.so" > /etc/php/7.4/apache2/conf.d/20-oci8.ini
