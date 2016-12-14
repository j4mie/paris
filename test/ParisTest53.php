<?php

namespace Paris\Tests;
use ORM, Model, MockPDO, PHPUnit_Framework_TestCase;

class ParisTest53 extends PHPUnit_Framework_TestCase {

    public function setUp() {
        // Enable logging
        ORM::configure('logging', true);

        // Set up the dummy database connection
        $db = new MockPDO('sqlite::memory:');
        ORM::set_db($db);
    }

    public function tearDown() {
        ORM::configure('logging', false);
        ORM::set_db(null);
    }



    public function testNamespacedTableName() {
        Model::factory('Paris\Tests\Simple')->find_many();
        $expected = 'SELECT * FROM `paris_tests_simple`';
        $this->assertEquals($expected, ORM::get_last_query());

        MustNotIgnoreNamespace::find_many();
        $expected = 'SELECT * FROM `paris_tests_must_not_ignore_namespace`';
        $this->assertEquals($expected, ORM::get_last_query());

        Model::$short_table_names = true;
        MustNotIgnoreNamespace::find_many();
        $expected = 'SELECT * FROM `paris_tests_must_not_ignore_namespace`';
        $this->assertEquals($expected, ORM::get_last_query());

        Model::$short_table_names = false;
        MustUseGlobalNamespaceConfig::find_many();
        $expected = 'SELECT * FROM `paris_tests_must_use_global_namespace_config`';
        $this->assertEquals($expected, ORM::get_last_query());

        Model::$short_table_names = false;
        MustNotIgnoreNamespace::find_many();
        $expected = 'SELECT * FROM `paris_tests_must_not_ignore_namespace`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testIgnoredNamespaceTableName() {
        MustIgnoreNamespace::find_many();
        $expected = 'SELECT * FROM `must_ignore_namespace`';
        $this->assertEquals($expected, ORM::get_last_query());

        Model::$short_table_names = true;
        MustIgnoreNamespace::find_many();
        $expected = 'SELECT * FROM `must_ignore_namespace`';
        $this->assertEquals($expected, ORM::get_last_query());

        Model::$short_table_names = true;
        MustUseGlobalNamespaceConfig::find_many();
        $expected = 'SELECT * FROM `must_use_global_namespace_config`';
        $this->assertEquals($expected, ORM::get_last_query());

        Model::$short_table_names = false;
        MustIgnoreNamespace::find_many();
        $expected = 'SELECT * FROM `must_ignore_namespace`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testModelWithCustomTable() {
        Model::factory('ModelWithCustomTable')->find_many();
        $expected = 'SELECT * FROM `custom_table`';
        $this->assertEquals($expected, ORM::get_last_query());
    }
    
    public function testShortcut() {
        \Paris\Tests\Simple::find_many();
        $expected = 'SELECT * FROM `paris_tests_simple`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

}

class Simple extends Model { }
class ModelWithCustomTable extends Model {
    public static $_table = 'custom_table';
}
class MustIgnoreNamespace extends Model {
    public static $_table_use_short_name = true;
}
class MustNotIgnoreNamespace extends Model {
    public static $_table_use_short_name = false;
}
class MustUseGlobalNamespaceConfig extends Model {
    public static $_table_use_short_name = null;
}
