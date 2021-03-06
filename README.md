# ToDoList

[![SymfonyInsight](https://insight.symfony.com/projects/5237d936-c1e7-4301-ab67-c3e090224d7b/mini.svg)](https://insight.symfony.com/projects/5237d936-c1e7-4301-ab67-c3e090224d7b)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/9cbfa619b9ed44a4b477095226115de4)](https://www.codacy.com/gh/kdefarge/ToDoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kdefarge/ToDoList&amp;utm_campaign=Badge_Grade)
[![Build Status](https://www.travis-ci.com/kdefarge/ToDoList.svg?branch=master)](https://www.travis-ci.com/kdefarge/ToDoList)
[![codecov](https://codecov.io/gh/kdefarge/ToDoList/branch/master/graph/badge.svg?token=I18BX5D0I4)](https://codecov.io/gh/kdefarge/ToDoList)
[![Maintainability](https://api.codeclimate.com/v1/badges/222e8cafac94fd6c0eab/maintainability)](https://codeclimate.com/github/kdefarge/ToDoList/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/222e8cafac94fd6c0eab/test_coverage)](https://codeclimate.com/github/kdefarge/ToDoList/test_coverage)

ToDoList is a Symfony web application

## Table of Contents

-   [Installation](#Installation)
-   [Setup](#Setup)
-   [Running](#Running)
-   [Fixture](#Fixture)
-   [Test](#Test)
-   [How contribute](#How-contribute)
-   [Tools used](#Tools-used)
-   [Maintainers](#Maintainers)
-   [License](#License)

## Require

-   Install PHP 7.1 or higher and these PHP extensions (which are installed and enabled by default in most PHP 7 installations): Ctype, iconv, JSON, PCRE, Session, SimpleXML, and Tokenizer;
-   Install Composer, which is used to install PHP packages.
-   These application support relational databases like MySQL and PostgreSQL

## Installation

### install ToDoList with composer

```bash
git clone git@github.com:kdefarge/ToDoList.git
cd ToDoList
composer install
```

## Setup

### Update .env file

```bash
# Config database
# MariaDB (dont forget version X.X.X with your version)
DATABASE_URL="mysql://USER:PASSWRD@SERVER:PORT/DB_NAME?serverVersion=mariadb-X.X.X"
```

### Install database

```bash
# Doctrine can create the DB_NAME database for you
php bin/console doctrine:database:create
# Create database schema
php bin/console doctrine:schema:create
```

## Running

```bash
symfony server:start
```

Open your browser and navigate to http://localhost:8000/. If everything is working, you???ll see a welcome page. Later, when you are finished working, stop the server by pressing Ctrl+C from your terminal.

To access the API documentation navigate to https://localhost:8000/docs

## Fixture

### Run dev fixture

```bash
# load all the 'dev' fixtures
php bin/console hautelook:fixtures:load
```

## Test

### Select your PHPUnit version

```xml
<!-- phpunit.xml.dist -->
<server name="SYMFONY_PHPUNIT_VERSION" value="9.5.4" />
```

### Run all test

```bash
php bin/phpunit
```

### Run one part test

```bash
php bin/phpunit --filter test
```

### Test other database

Update .env.test and DATABASE_URL then create the database with schema

```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
```

### PHPUnit help

```bash
php bin/phpunit --help
```

### Code coverage

```bash
vendor/bin/phpunit --coverage-html code-coverage
```

## How contribute

[This documentation explains how you contributed](https://github.com/kdefarge/ToDoList/blob/master/docs/CONTRIBUTE.md)

## Tools used

-   [Symfony](https://github.com/symfony/symfony)
-   [PHPUnit](https://github.com/sebastianbergmann/phpunit)
-   [AliceBundle](https://github.com/hautelook/AliceBundle)

## Maintainers

[@kdefarge](https://github.com/kdefarge)

## License

[MIT](https://github.com/kdefarge/BileMoAPI/blob/master/LICENSE.md) ?? K??vin DEFARGE
