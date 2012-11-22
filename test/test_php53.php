<?php

    namespace {

        /*
         * Testing for Paris for features specific to php5.3
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

        class PrefixSimple extends Model {
        }

        class PrePrefixSimple extends Model {
        }

    }

    namespace Tests {

        use ORM, Model,DummyPDO, Tester;

        class Simple extends Model {
        }

        class PrefixSimple extends Model {
        }

        class PrePrefixSimple extends Model {
        }

    }

    namespace Tests2 {

        use ORM, Model,DummyPDO, Tester;

        class Simple extends Model {
        }

        class PrefixSimple extends Model {
        }

        class PrePrefixSimple extends Model {
        }

    }

    namespace {

        // Configured prefix
        ORMWrapper::configure('class_prefix', 'Prefix');
        Model::factory('Simple')->find_many();
        $expected = 'SELECT * FROM `prefix_simple`';
        Tester::check_equal("Prefixed auto table name", $expected);

        // Configured prefix is the class prefix so is ignored
        Model::factory('PrefixSimple')->find_many();
        $expected = 'SELECT * FROM `prefix_simple`';
        Tester::check_equal("Configured prefix ignored when already present", $expected);

        // Configured prefix is a substring of class prefix so is not ignored
        ORMWrapper::configure('class_prefix', 'Pre');
        Model::factory('PrefixSimple')->find_many();
        $expected = 'SELECT * FROM `pre_prefix_simple`';
        Tester::check_equal("Configured prefix used when not exactly the class prefix", $expected);

        // Simple namespace
        ORMWrapper::configure('class_prefix', '');
        Model::factory('Tests\\Simple')->find_many();
        $expected = 'SELECT * FROM `tests_simple`';
        Tester::check_equal("Namespaced auto table name", $expected);

        // Normalised simple namespace
        Model::factory('\\Tests\\Simple')->find_many();
        $expected = 'SELECT * FROM `tests_simple`';
        Tester::check_equal("Normalised namespaced auto table name", $expected);

        // Configured namespace
        ORMWrapper::configure('namespace', 'Tests');
        Model::factory('Simple')->find_many();
        $expected = 'SELECT * FROM `tests_simple`';
        Tester::check_equal("Configured namespaced auto table name", $expected);

        // Normalised configured namespace
        ORMWrapper::configure('namespace', '\\Tests\\');
        Model::factory('Simple')->find_many();
        $expected = 'SELECT * FROM `tests_simple`';
        Tester::check_equal("Normalised configured namespaced auto table name", $expected);

        // Supplied namespace overrides configured namespace
        ORMWrapper::configure('namespace', 'Tests');
        Model::factory('Tests2\\Simple')->find_many();
        $expected = 'SELECT * FROM `tests2_simple`';
        Tester::check_equal("Supplied namespace overrides configured namespace", $expected);

        // Configured prefix and supplied namespace
        ORMWrapper::configure('class_prefix', 'Prefix');
        ORMWrapper::configure('namespace', '');
        Model::factory('Tests\\Simple')->find_many();
        $expected = 'SELECT * FROM `tests_prefix_simple`';
        Tester::check_equal("Configured prefix prepended to class base name", $expected);

    }