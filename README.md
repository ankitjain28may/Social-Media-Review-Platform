# Social-Media-Review-Platform

It is a platform to keep an eye on all the posts shared, liked and commented by the people hired by you for marketing.

# Installation and Contribution

### Requirements :

1. PHP >= 7.0
2. MySQL
3. Composer
4. Laravel >= 5.4

### Installation :

Fork and Clone this repo or download it on your local system.

Open composer and run this given command.

```shell
composer install
composer update
```

After installing composer, Rename the file `.env.example` to `.env`.

```shell
cp .env.example .env
```

Generate the Application key

```php
php artisan key:generate
```

Migrate the database.

```php
php artisan migrate
```

Set db credentials in `.env` and run the project.

Set Facebook and Twitter API keys in your .env file.

Run this project on localhost

```php
php artisan serve
```

This project will run on this server:

```
http://localhost:8000/
```