<p align="center">
    <h1 align="center">Система управления пользователями и городами</h1>
    <br>
</p>


ТРЕБОВАНИЯ
------------

PHP 7.1<br>
PDO


УСТАНОВКА
------------

1) В веб-директорию (или необходимую субдиректорию в веб-директории):<br>
`git clone https://github.com/LadlenDUImap/livemaster.git .`

2) Для установки PHPUnit (в той же директории):<br>
`composer install`

3) Директории `/runtime/log` и `/tests/runtime/log` должны иметь права на запись веб-клиентом.<br>
Примечание: можно настроить другие папки для логов в файлах `/app/config/add.php` и `/tests/config/add.php`.

4) Используйте файлы `/app/data/db/db.sql` и `/app/data/db/db_test.sql` для создания баз данных MySql (рабочей и тестовой) и заполнения их табличными данными.<br> 
Внимание! При использовании вышеуказанных файлов будут созданы и заполнены базы данных `livemaster` и `livemaster_test`. Скорректируйте файлы если этот вариант не подходит.

5) Настройте параметры доступа к базе данных в файлах `/app/config/db.php` и `/test/config/db.php` - установите значения dsn, username, password.


ЗАПУСК PHPUnit ТЕСТОВ
------------

Выполните команду в директории проекта:<br>
`./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/.`
