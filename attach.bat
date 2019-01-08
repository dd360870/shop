@echo off
IF "%1"=="php" goto php_ruzy
IF "%1"=="nginx" goto nginx_root
echo usage: attach [container] [user]
goto exit

:php_ruzy
docker exec -it --user 1000:1000 -w /var/www/html shop_php_1 /bin/bash
goto exit

:nginx_root
docker exec -it -w /usr/share/nginx/html shop_nginx_1 /bin/bash
goto exit

:exit
