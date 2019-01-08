FROM nginx:1.15.2
MAINTAINER ruzy
COPY ./configs /configs
# copy SSL file
RUN cp -r /configs/ssl /etc/nginx/ssl &&\
    rm -r /configs/ssl
