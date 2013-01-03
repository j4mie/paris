Configuration
=============

Setup
~~~~~

Paris requires `Idiorm`_. Install Idiorm and Paris somewhere in your
project directory, and ``require`` both.

::

    require_once 'your/path/to/idiorm.php';
    require_once 'your/path/to/paris.php';

Then, you need to tell Idiorm how to connect to your database. **For
full details of how to do this, see `Idiorm’s documentation`_.**

Briefly, you need to pass a *Data Source Name* connection string to the
``configure`` method of the ORM class.

::

    ORM::configure('sqlite:./example.db');

You may also need to pass a username and password to your database
driver, using the ``username`` and ``password`` configuration options.
For example, if you are using MySQL:

::

    ORM::configure('mysql:host=localhost;dbname=my_database');
    ORM::configure('username', 'database_user');
    ORM::configure('password', 'top_secret');

Configuration
~~~~~~~~~~~~~

The only configuration options provided by Paris itself are the
``$_table`` and ``$_id_column`` static properties on model classes. To
configure the database connection, you should use Idiorm’s configuration
system via the ``ORM::configure`` method. **See `Idiorm’s
documentation`_ for full details.**

Query logging
~~~~~~~~~~~~~

Idiorm can log all queries it executes. To enable query logging, set the
``logging`` option to ``true`` (it is ``false`` by default).

::

    ORM::configure('logging', true);

When query logging is enabled, you can use two static methods to access
the log. ``ORM::get_last_query()`` returns the most recent query
executed. ``ORM::get_query_log()`` returns an array of all queries
executed.

.. _Idiorm’s documentation: http://github.com/j4mie/idiorm/
.. _Idiorm: http://github.com/j4mie/idiorm/