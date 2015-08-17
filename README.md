ZendSkeletonApplication
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
