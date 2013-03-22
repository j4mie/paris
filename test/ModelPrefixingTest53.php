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

        public function testPrefixOnHasManyThroughRelation() {
            Model::$auto_prefix_models = '\\Tests3\\';
            $book = Model::factory('Book')->find_one(1);
            $authors = $book->authors()->find_many();
            $expected = "SELECT `prefix_author`.* FROM `prefix_author` JOIN `prefix_authorbook` ON `prefix_author`.`id` = `prefix_authorbook`.`prefix_author_id` WHERE `prefix_authorbook`.`prefix_book_id` = '1'";
            $this->assertEquals($expected, ORM::get_last_query());
        }

        public function testPrefixOnHasManyThroughRelationWithCustomIntermediateModelAndKeyNames() {
            Model::$auto_prefix_models = '\\Tests3\\';
            $book2 = Model::factory('BookTwo')->find_one(1);
            $authors2 = $book2->authors()->find_many();
            $expected = "SELECT `prefix_author`.* FROM `prefix_author` JOIN `prefix_authorbook` ON `prefix_author`.`id` = `prefix_authorbook`.`custom_author_id` WHERE `prefix_authorbook`.`custom_book_id` = '1'";
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
namespace Tests3 { 
    use ORM, Model, MockPDO; 
	class Author extends Model {
		public static $_table = 'prefix_author';
	}
	class AuthorBook extends Model {
		public static $_table = 'prefix_authorbook';
	}
	class Book extends Model {
		public static $_table = 'prefix_book';
		public function authors() {
			return $this->has_many_through('Author');
		}
	}
	class BookTwo extends Model {
		public static $_table = 'prefix_booktwo';
		public function authors() {
			return $this->has_many_through('Author', 'AuthorBook', 'custom_book_id', 'custom_author_id');
		}
	}
}