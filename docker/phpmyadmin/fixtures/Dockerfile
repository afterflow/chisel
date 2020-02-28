FROM phpmyadmin/phpmyadmin:edge
# Add volume for sessions to allow session persistence
VOLUME /sessions
# We expose phpMyAdmin on port 80
EXPOSE 80

COPY config.user.inc.php /etc/phpmyadmin/config.user.inc.php

ARG MSQT
ENV MSQT ${MSQT}

RUN echo "\$cfg[ 'Servers' ][ 1 ][ 'host' ] = '$MSQT';" >> /etc/phpmyadmin/config.user.inc.php
