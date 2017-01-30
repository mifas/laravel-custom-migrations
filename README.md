## Laravel Custom Migrations

[![Documentation Status](https://readthedocs.org/projects/laravel-custom-migrations/badge/?version=latest)](https://readthedocs.org/projects/laravel-custom-migrations/?badge=latest)
[![Latest Stable Version](https://poser.pugx.org/codengine/laravel-custom-migrations/version.png)](https://packagist.org/packages/codengine/laravel-custom-migrations) [![Total Downloads](https://poser.pugx.org/codengine/laravel-custom-migrations/d/total.png)](https://packagist.org/packages/codengine/laravel-custom-migrations) [![Build Status](https://travis-ci.org/codengine/laravel-custom-migrations.svg?branch=master)](https://travis-ci.org/codengine/laravel-custom-migrations) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/codengine/laravel-custom-migrations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/codengine/laravel-custom-migrations/?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/5550bb35f7db0d2f07000371/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5550bb35f7db0d2f07000371)

This is an update for those who maintain database with host configuration

Fork from:
https://github.com/codengine/laravel-custom-migrations

### Run separate Laravel migrations on multiple databases ###

This package provides a simple way of including different types of migrations over multiple databases. A common use case would be that you are running a main database and separate databases for each customer.

It is a bit painful to run each migration independently for each database connection. You are also not able to differ which migration is responsible for which type of migration.

**Custom Migrations to the Aid!**

### Compatibility ###
Laravel Version|Version
---------------|-------
4.x            |1.0.*
5.0            |1.1.*
5.1            |1.2.*
5.2            |1.3.*

### Installation ###
To install this package, simply put the following into your `composer.json`

```json
{
    "require": {
        "codengine/laravel-custom-migrations": "1.3.*"
    }
}
```

or for **Laravel 4.x**:

```json
{
    "require": {
        "codengine/laravel-custom-migrations": "1.0.*"
    }
}
```
    
After updating composer, replace the default Laravel Migration Service Provider

    'Illuminate\Database\MigrationServiceProvider',
    
with
    
    'Codengine\CustomMigrations\CustomMigrationsServiceProvider',
    
You should then update the laravel autoload files with
    
    php artisan dump-autoload
    
    
### Configuration ###
In your database.php file, you have to specify the type of the database which will later be used to decide which migrations apply for them. Here is an example:

	'my_customer' => array(
		'driver'    => 'mysql',
		'host'      => 'localhost',
		'database'  => 'customer',
		'username'  => 'customer',
		'password'  => '',
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => '',
		'migration_type' => 'customer'
	)		
	
The important part is **"migration_type"**. It does not have to be unique.

### Usage ###
Each migration file needs to be type-hinted as we need to differ between default migrations and custom ones.

You have to specify the type of the migration in your migration class like this:

    class MyMigration {
        public $type = "customer";
        
        public function up(){
        ....
    }
    
It has to be public and should be the same as in your database configuration. If a $type is not specified, the migration will only be used for the default database.

### Running Custom Migrations ###
As this package only extends the Laravel commands, every command like "migrate", "rollback", "reset" and "refresh" works like it did before.

There is just one difference:
Each command got a new option: **--type=xyz**. So, in order to run the "customer" migrations for each customer-related database you just have to execute the command like this:

    php artisan migrate --type=customer
    php artisan migrate:rollback --type=customer
    php artisan migrate:refresh --type=customer
    php artisan migrate:reset --type=customer
    
It will iterate over each customer database connection and run the applicable migrations.

You can also limit the migration to just one database using the **--database** option like this
    
    php artisan migrate --type=customer --database=mySpecialCustomer

### License
The Laravel Custom Migrations Package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
