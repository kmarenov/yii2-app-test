# Yii 2 Test Application

## Содержание
- [Загрузка](#загрузка)
    - [Используя Composer](#используя-composer)
    - [Используя Git](#используя-git)
- [Настройка соединения с базой данных](#настройка-соединения-с-базой-данных)
- [Развертывание базы данных](#развертывание-базы-данных)
    - [Используя миграции](#используя-миграции)
    - [Используя дамп базы данных](#используя-дамп-базы-данных)
- [Запуск](#запуск)
    - [Используя встроенный в PHP веб-сервер](#используя-встроенный-в-php-веб-сервер)
    - [Используя Vagrant](#используя-vagrant)

##Загрузка
### Используя Composer
Загрузить приложение можно с помощью [Composer](https://getcomposer.org/):

```
$ composer create-project --prefer-dist --stability=dev kmarenov/yii2-app-test
```
### Используя Git
Также можно клонировать репозиторий проекта с [GitHub](https://github.com/) используя [Git](https://git-scm.com/):

```
$ git clone https://github.com/kmarenov/yii2-app-test.git
```

И затем установить с помощью [Composer](https://getcomposer.org/) все необходимые пакеты зависимостей:

```
$ сd yii2-app-test
$ composer install --prefer-dist 
```

##Настройка соединения с базой данных

В файле `config/db.php` необходимо указать параметры подключения к базе данных:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=test1',
    'username' => 'root',
    'password' => 'mysqlroot',
    'charset' => 'utf8',
];
```
Саму базу данных необходимо создать вручную.

##Развертывание базы данных
###Используя миграции

Создать все таблицы, необходимые для работы приложения, и наполнить их тестовыми данными можно используя миграции.
Для этого необходимо запустить их с помощью консольного приложения `yii`

```
$ сd yii2-app-test
$ ./yii migrate
```

Выполнение миграций может занять довольно длительное время в связи с тем, что происходит наполнение базы данных
большим количеством тестовых записей, поэтому рекомендуется разворачивать базу данных используя готовый дамп
базы данных, созданной при помощи данных миграций.

###Используя дамп базы данных

В корневом каталоге проекта находится файл `test.sql` c дампом базы данных, развернув который можно получить готовую 
базу данных, содержащую всё необходимое для работы приложения, включая тестовые записи.

Развернуть дамп можно с помощью утилиты `mysql`:

```
$ сd yii2-app-test
$ mysql -u root -p test < test.sql
```

##Запуск

В том случае, если запуск приложения производится с помощью какого-либо веб-сервера 
(например [Apache HTTP Server](http://httpd.apache.org/)), то располагать приложение следует таким образом, чтобы
корневым каталогом веб-сервера являлся каталог приложения `/web`

###Используя встроенный в PHP веб-сервер

Запустить приложения можно используя встроенный в PHP веб-сервер. Для этого сервер нужно запустить из каталога `/web`

```
$ сd yii2-app-test/web
$ php -S localhost:8888 
```
После этого приложение будет доступно по адресу [http://localhost:8888/](http://localhost:8888/)

###Используя Vagrant

Проект содержит готовые файлы конфигурации для [Vagrant](https://www.vagrantup.com/).

Для запуска с использованием Vagrant у вас должны быть установлены [VirtualBox](https://www.virtualbox.org/) и
[Vagrant](https://www.vagrantup.com/). Также для Vagrant рекомендуется установить плагин vagrant-vbguest,
который автоматически разрешает ситуацию в том случае, если версии VirtualBox Guest Additions на вашем компьютере
и внутри виртуальной машины различаются:

```
$ vagrant plugin install vagrant-vbguest 
```

Запуск виртуальной машины со всем необходимым окружением осуществляется с помощью команды `vagrant up` из
каталога проекта:

```
$ сd yii2-app-test
$ vagrant up
```

В том случае, если запуск приложения осуществляется с помощью Vagrant, нет необходимости вручную создавать базу данных.
Она автоматически создастся при развертывании виртуальной машины, и приложение корректно функционирует используя
те параметры подключения к базе данных, которые уже прописаны в файле `config/db.php`.

После запуска виртуальной машины приложение будет доступно по адресу [http://localhost:8888/](http://localhost:8888/)

Остановить виртуальную машину можно выполнив из каталога проекта команду `vagrant halt`:

```
$ сd yii2-app-test
$ vagrant halt
```