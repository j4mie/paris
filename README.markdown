Paris
=====

[![Build Status](https://travis-ci.org/j4mie/paris.png?branch=master)](https://travis-ci.org/j4mie/paris)

[http://j4mie.github.com/idiormandparis/](http://j4mie.github.com/idiormandparis/)

A lightweight Active Record implementation for PHP5.

Built on top of [Idiorm](http://github.com/j4mie/idiorm/).

Tested on PHP 5.2.0+ - may work on earlier versions with PDO and the correct database drivers.

Released under a [BSD license](http://en.wikipedia.org/wiki/BSD_licenses).

Features
--------

* Extremely simple configuration.
* Exposes the full power of [Idiorm](http://github.com/j4mie/idiorm/)'s fluent query API.
* Supports associations.
* Simple mechanism to encapsulate common queries in filter methods.
* Built on top of [PDO](http://php.net/pdo).
* Uses [prepared statements](http://uk.php.net/manual/en/pdo.prepared-statements.php) throughout to protect against [SQL injection](http://en.wikipedia.org/wiki/SQL_injection) attacks.
* Database agnostic. Currently supports SQLite, MySQL, Firebird and PostgreSQL. May support others, please give it a try!
* Supports collections of models with method chaining to filter or apply actions to multiple results at once.
* Multiple connections are supported

Documentation
-------------

The documentation is hosted on Read the Docs: [paris.rtfd.org](http://paris.rtfd.org)

### Building the Docs ###

You will need to install [Sphinx](http://sphinx-doc.org/) and then in the docs folder run:

    make html

The documentation will now be in docs/_build/html/index.html

Let's See Some Code
-------------------
```php
class User extends Model {
    public function tweets() {
        return $this->has_many('Tweet');
    }
}

class Tweet extends Model {}

$user = Model::factory('User')
    ->where_equal('username', 'j4mie')
    ->find_one();
$user->first_name = 'Jamie';
$user->save();

$tweets = $user->tweets()->find_many();
foreach ($tweets as $tweet) {
    echo $tweet->text;
}
```

Changelog
---------

#### 1.3.0 - released 2013-01-31

* Documentation moved to [paris.rtfd.org](http://paris.rtfd.org) and now built using [Sphinx](http://sphinx-doc.org/)
* Add support for multiple database connections - closes [issue #15](https://github.com/j4mie/idiorm/issues/15) [[tag](https://github.com/tag)]
* Allow a prefix for model class names - see Configuration in the documentation - closes [issues #33](https://github.com/j4mie/paris/issues/33)
* Exclude tests and git files from git exports (used by composer)
* Implement `set_expr` - closes [issue #39](https://github.com/j4mie/paris/issues/39)
* Add `is_new` - closes [issue #40](https://github.com/j4mie/paris/issues/40)
* Add support for the new IdiormResultSet object in Idiorm - closes [issue #14](https://github.com/j4mie/paris/issues/14)
* Change Composer to use a classmap so that autoloading is better supported [[javierd](https://github.com/javiervd)] - [issue #44](https://github.com/j4mie/paris/issues/44)
* Move tests into PHPUnit to match Idiorm
* Update included Idiorm version for tests
* Move documentation to use Sphinx

#### 1.2.0 - released 2012-11-14

* Setup composer for installation via packagist (j4mie/paris)
* Add in basic namespace support, see [issue #20](https://github.com/j4mie/paris/issues/20)
* Allow properties to be set as an associative array in `set()`, see [issue #13](https://github.com/j4mie/paris/issues/13)
* Patch in idiorm now allows empty models to be saved (j4mie/idiorm see [issue #58](https://github.com/j4mie/paris/issues/58))

#### 1.1.1 - released 2011-01-30

* Fix incorrect tests, see [issue #12](https://github.com/j4mie/paris/issues/12)

#### 1.1.0 - released 2011-01-24

* Add `is_dirty` method

#### 1.0.0 - released 2010-12-01

* Initial release
