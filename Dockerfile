FROM ubuntu:16.04

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install php7.0 php7.0-mysql -y
RUN apt-get install apache2 libapache2-mod-php7.0 -y
RUN apt-get install golang-go -y

RUN a2enmod php7.0

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

EXPOSE 80

WORKDIR /var/www/dollywood
ADD . /var/www/dollywood

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN echo "<VirtualHost *:80>\n\
                 DocumentRoot /var/www/dollywood/app/web\n\
         </VirtualHost>" > /etc/apache2/sites-enabled/000-default.conf

CMD /usr/sbin/apache2ctl -D FOREGROUND