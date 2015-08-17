Zend Framework 2 RESTful application
=======================

Introduction
------------
This is a simple, RESTful application using the ZF2 MVC layer and module
systems. This application is meant to be used as a starting place for those
looking to get their feet wet with ZF2.

Installation
---------------------------

    git clone https://github.com/cmyker/zf2-rest-example

In your project root:

    composer install

Database schema is in data/shema.sql file. Create schema and configure database credentials in local.php file

    cp config/autoload/local.php.dist config/autoload/local.php

### PHP CLI server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root
directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

Running tests
---------------------------

    vendor/bin/phpunit test/
