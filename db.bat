@echo off
IF "%1"=="backup" goto backup
IF "%1"=="restore" goto restore
echo usage: db [backup/restore]
goto exit

:backup
docker exec docker-test_db_1 /usr/bin/mysqldump -u root --password=secret shop > data/mariadb/backup.sql
goto exit

:restore
docker exec -it docker-test_db_1 /usr/bin/mysql -u root --password=secret shop
goto exit

:exit