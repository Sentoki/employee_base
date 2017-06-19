Employee base
============================

1. Клонировать репозиторий

```bash
git clone https://github.com/Sentoki/employee_base.git
```

2. Указать в конфигурации nginx каталог web фреймворка

3. Создать копию файла config/db.php.dist, назвать db.php и указать актуальные реквизиты доступа
к базе данных

4. Выполнить команду

 ```bash
 composer install --no-dev
 ```

5. Применить миграции базы данных

```bash
./yii migrate
```

6. Если требуется запустить тесты, нужно установить phpunit с помощью команды

```bash
 composer install
 ```
 
И запустить тесты

```bash
./vendor/bin/phpunit
```