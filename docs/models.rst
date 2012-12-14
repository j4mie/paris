Models
======

Model Classes
~~~~~~~~~~~~~

You should create a model class for each entity in your application. For
example, if you are building an application that requires users, you
should create a ``User`` class. Your model classes should extend the
base ``Model`` class:

::

    class User extends Model {
    }

Paris takes care of creating instances of your model classes, and
populating them with *data* from the database. You can then add
*behaviour* to this class in the form of public methods which implement
your application logic. This combination of data and behaviour is the
essence of the `Active Record pattern`_.

Database Tables
~~~~~~~~~~~~~~~

Your ``User`` class should have a corresponding ``user`` table in your
database to store its data.

By default, Paris assumes your class names are in *CapWords* style, and
your table names are in *lowercase\_with\_underscores* style. It will
convert between the two automatically. For example, if your class is
called ``CarTyre``, Paris will look for a table named ``car_tyre``.

To override this default behaviour, add a **public static** property to
your class called ``$_table``:

::

    class User extends Model {
        public static $_table = 'my_user_table';
    }

ID Column
~~~~~~~~~

Paris requires that your database tables have a unique primary key
column. By default, Paris will use a column called ``id``. To override
this default behaviour, add a **public static** property to your class
called ``$_id_column``:

::

    class User extends Model {
        public static $_id_column = 'my_id_column';
    }

**Note** - Paris has its *own* default ID column name mechanism, and
does not respect column names specified in Idiorm’s configuration.

.. _Active Record pattern: http://martinfowler.com/eaaCatalog/activeRecord.html