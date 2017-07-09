FROM debian:jessie

MAINTAINER Okesii Dubinin <oleksii.dubinin@gmail.com>

RUN apt-get update && apt-get install -y \
    nginx

ADD nginx.conf /etc/nginx/
ADD app.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/app.conf /etc/nginx/sites-enabled/app.conf
RUN rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf
RUN echo 'alias ll="ls -la"' >> ~/.bashrc
RUN usermod -u 1000 www-data
RUN apt-get install nano

CMD ["nginx"]

EXPOSE 80
