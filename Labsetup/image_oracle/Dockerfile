# Labsetup/image_oracle/Dockerfile
FROM oracle-23ai

USER root
RUN mkdir -p /opt/lab
COPY sqllab_users_oracle.sql /opt/lab/sqllab_users_oracle.sql
COPY init_oracle.sh /opt/lab/init_oracle.sh
RUN chmod +x /opt/lab/init_oracle.sh

USER oracle
