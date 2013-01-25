Paris
=====

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
* Database agnostic. Currently supports SQLite and MySQL. May support others, please give it a try!
* Supports collections of models with method chaining to filter or apply actions to multiple results at once.
* Multiple connections are supported

Documentation
-------------

The documentation is hosted on Read the Docs: [paris.rtfd.org](http://paris.rtfd.org)

### Building the Docs ###

You will need to install [Sphinx](http://sphinx-doc.org/) and then in the docs folder run:

    make html

The documentation will now be in docs/_build/html/index.html

Changelog
---------

#### 1.3.0 - released XXXX-XX-XX

* Add support for multiple database connections
* Exclude tests and git files from git exports (used by composer)
* Update included Idiorm version for tests
* Implement `set_expr` - closes issue #39
* Add `is_new` - closes issue #40
* Add support for the new IdiormResultSet object
* Change Composer to use a classmap so that autoloading is better supported [[javierd](https://github.com/javiervd)] - issue #44
* Move tests into PHPUnit to match Idiorm
* Move documentation to use Sphinx

#### 1.2.0 - released 2012-11-14

* Setup composer for installation via packagist (j4mie/paris)
* Add in basic namespace support, see issue #20
* Allow properties to be set as an associative array in `set()`, see issue #13
* Patch in idiorm now allows empty models to be saved (j4mie/idiorm see issue #58)

#### 1.1.1 - released 2011-01-30

* Fix incorrect tests, see issue #12

#### 1.1.0 - released 2011-01-24

* Add `is_dirty` method

#### 1.0.0 - released 2010-12-01

* Initial release
