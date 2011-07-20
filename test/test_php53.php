<?php

    namespace Tests;

    use ORM, Model,DummyPDO, Tester;

    /*
     * Testing for Paris for features specifics to PHP >= 5.3
     *
     * We deliberately don't test the query API - that's Idiorm's job.
     * We just test Paris-specific functionality.
     *
     * Checks that the generated SQL is correct
     *
     */

    require_once dirname(__FILE__) . "/idiorm.php";
    require_once dirname(__FILE__) . "/../paris.php";
    require_once dirname(__FILE__) . "/test_classes.php";

    // Enable logging
    ORM::configure('logging', true);

    // Set up the dummy database connection
    $db = new DummyPDO('sqlite::memory:');
    ORM::set_db($db);

    class Simple extends Model {
    }

    Model::factory('Tests\Simple')->find_many();
    $expected = 'SELECT * FROM `tests_simple`';
    Tester::check_equal("Namespaced auto table name", $expected);
