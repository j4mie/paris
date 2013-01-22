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
    }

    public function testModelWithCustomTable() {
        Model::factory('ModelWithCustomTable')->find_many();
        $expected = 'SELECT * FROM `custom_table`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

}

class Simple extends Model { }
class ModelWithCustomTable extends Model {
    public static $_table = 'custom_table';
}