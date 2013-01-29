<?php

namespace Paris\Tests {
    use ORM, Model, MockPDO, PHPUnit_Framework_TestCase;

    class ModelPrefixingTest53 extends PHPUnit_Framework_TestCase {

        public function setUp() {
            // Set up the dummy database connection
            ORM::set_db(new MockPDO('sqlite::memory:'));

            // Enable logging
            ORM::configure('logging', true);

            Model::$auto_prefix_models = null;
        }

        public function tearDown() {
            ORM::configure('logging', false);
            ORM::set_db(null);

            Model::$auto_prefix_models = null;
        }

        public function testNoPrefixOnAutoTableName() {
            Model::$auto_prefix_models = null;
            Model::factory('\Tests\Simple')->find_many();
            $expected = 'SELECT * FROM `tests_simple`';
            $this->assertEquals($expected, ORM::get_last_query());
        }

        public function testPrefixOnAutoTableName() {
            Model::$auto_prefix_models = '\\Tests\\';
            Model::factory('Simple')->find_many();
            $expected = 'SELECT * FROM `tests_simple`';
            $this->assertEquals($expected, ORM::get_last_query());
        }

        public function testPrefixOnAutoTableNameWithTableSpecified() {
            Model::$auto_prefix_models = '\\Tests\\';
            Model::factory('TableSpecified')->find_many();
            $expected = 'SELECT * FROM `simple`';
            $this->assertEquals($expected, ORM::get_last_query());
        }

        public function testNamespacePrefixSwitching() {
            Model::$auto_prefix_models = '\\Tests\\';
            Model::factory('TableSpecified')->find_many();
            $expected = 'SELECT * FROM `simple`';
            $this->assertEquals($expected, ORM::get_last_query());

            Model::$auto_prefix_models = '\\Tests2\\';
            Model::factory('TableSpecified')->find_many();
            $expected = 'SELECT * FROM `simple`';
            $this->assertEquals($expected, ORM::get_last_query());
        }
    }
}

namespace Tests {
    use ORM, Model, MockPDO;
    class Simple extends Model { }
    class TableSpecified extends Model {
        public static $_table = 'simple';
    }
}
namespace Tests2 { 
    use ORM, Model, MockPDO; 
    class Simple extends Model { }
    class TableSpecified extends Model {
        public static $_table = 'simple';
    }
}