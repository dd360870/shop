@echo off
docker run --rm -v %~dp0/root:/project -i -t --name test --user 1000:1000 -w /project/blog/public -p 80:80 ruzy:php sudo php -S 0.0.0.0:80