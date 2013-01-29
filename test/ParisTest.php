<?php

class ParisTest extends PHPUnit_Framework_TestCase {

    const ALTERNATE = 'alternate';

    public function setUp() {
        // Set up the dummy database connection
        ORM::set_db(new MockPDO('sqlite::memory:'));

        // Enable logging
        ORM::configure('logging', true);
    }

    public function tearDown() {
        ORM::configure('logging', false);
        ORM::set_db(null);
    }

    public function testSimpleAutoTableName() {
        Model::factory('Simple')->find_many();
        $expected = 'SELECT * FROM `simple`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testComplexModelClassName() {
        Model::factory('ComplexModelClassName')->find_many();
        $expected = 'SELECT * FROM `complex_model_class_name`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testModelWithCustomTable() {
        Model::factory('ModelWithCustomTable')->find_many();
        $expected = 'SELECT * FROM `custom_table`';
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testCustomIDColumn() {
        Model::factory('ModelWithCustomTableAndCustomIdColumn')->find_one(5);
        $expected = "SELECT * FROM `custom_table` WHERE `custom_id_column` = '5' LIMIT 1";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testFilterWithNoArguments() {
        Model::factory('ModelWithFilters')->filter('name_is_fred')->find_many();
        $expected = "SELECT * FROM `model_with_filters` WHERE `name` = 'Fred'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testFilterWithArguments() {
        Model::factory('ModelWithFilters')->filter('name_is', 'Bob')->find_many();
        $expected = "SELECT * FROM `model_with_filters` WHERE `name` = 'Bob'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testInsertData() {
        $widget = Model::factory('Simple')->create();
        $widget->name = "Fred";
        $widget->age = 10;
        $widget->save();
        $expected = "INSERT INTO `simple` (`name`, `age`) VALUES ('Fred', '10')";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testUpdateData() {
        $widget = Model::factory('Simple')->find_one(1);
        $widget->name = "Fred";
        $widget->age = 10;
        $widget->save();
        $expected = "UPDATE `simple` SET `name` = 'Fred', `age` = '10' WHERE `id` = '1'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testDeleteData() {
        $widget = Model::factory('Simple')->find_one(1);
        $widget->delete();
        $expected = "DELETE FROM `simple` WHERE `id` = '1'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testInsertingDataContainingAnExpression() {
        $widget = Model::factory('Simple')->create();
        $widget->name = "Fred";
        $widget->age = 10;
        $widget->set_expr('added', 'NOW()');
        $widget->save();
        $expected = "INSERT INTO `simple` (`name`, `age`, `added`) VALUES ('Fred', '10', NOW())";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testHasOneRelation() {
        $user = Model::factory('User')->find_one(1);
        $profile = $user->profile()->find_one();
        $expected = "SELECT * FROM `profile` WHERE `user_id` = '1' LIMIT 1";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testHasOneWithCustomForeignKeyName() {
        $user2 = Model::factory('UserTwo')->find_one(1);
        $profile = $user2->profile()->find_one();
        $expected = "SELECT * FROM `profile` WHERE `my_custom_fk_column` = '1' LIMIT 1";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testBelongsToRelation() {
        $user2 = Model::factory('UserTwo')->find_one(1);
        $profile = $user2->profile()->find_one();
        $profile->user_id = 1;
        $user3 = $profile->user()->find_one();
        $expected = "SELECT * FROM `user` WHERE `id` = '1' LIMIT 1";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testBelongsToRelationWithCustomForeignKeyName() {
        $profile2 = Model::factory('ProfileTwo')->find_one(1);
        $profile2->custom_user_fk_column = 5;
        $user4 = $profile2->user()->find_one();
        $expected = "SELECT * FROM `user` WHERE `id` = '5' LIMIT 1";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testHasManyRelation() {
        $user4 = Model::factory('UserThree')->find_one(1);
        $posts = $user4->posts()->find_many();
        $expected = "SELECT * FROM `post` WHERE `user_three_id` = '1'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testHasManyRelationWithCustomForeignKeyName() {
        $user5 = Model::factory('UserFour')->find_one(1);
        $posts = $user5->posts()->find_many();
        $expected = "SELECT * FROM `post` WHERE `my_custom_fk_column` = '1'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testHasManyThroughRelation() {
        $book = Model::factory('Book')->find_one(1);
        $authors = $book->authors()->find_many();
        $expected = "SELECT `author`.* FROM `author` JOIN `author_book` ON `author`.`id` = `author_book`.`author_id` WHERE `author_book`.`book_id` = '1'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

    public function testHasManyThroughRelationWithCustomIntermediateModelAndKeyNames() {
        $book2 = Model::factory('BookTwo')->find_one(1);
        $authors2 = $book2->authors()->find_many();
        $expected = "SELECT `author`.* FROM `author` JOIN `author_book` ON `author`.`id` = `author_book`.`custom_author_id` WHERE `author_book`.`custom_book_id` = '1'";
        $this->assertEquals($expected, ORM::get_last_query());
    }

}