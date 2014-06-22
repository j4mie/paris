<?php

require_once dirname(__FILE__) . '/idiorm.php';
require_once dirname(__FILE__) . "/../paris.php";

/**
 *
 * Mock version of the PDOStatement class.
 *
 */
class MockPDOStatement extends PDOStatement {

   private $current_row = 0;
   
   public function __construct() {}
   public function execute($params = array()) {}
   
   /**
    * Return some dummy data
    */
   public function fetch($fetch_style=PDO::FETCH_BOTH, $cursor_orientation=PDO::FETCH_ORI_NEXT, $cursor_offset=0) {
       if ($this->current_row == 5) {
           return false;
       } else {
           return array('name' => 'Fred', 'age' => 10, 'id' => ++$this->current_row);
       }
   }
}

/**
 *
 * Mock database class implementing a subset
 * of the PDO API.
 *
 */
class MockPDO extends PDO {

   /**
    * Return a dummy PDO statement
    */
   public function prepare($statement, $driver_options=array()) {
       $this->last_query = new MockPDOStatement($statement);
       return $this->last_query;
   }
}

/**
 * Another mock PDOStatement class, used for testing multiple connections
 */
class MockDifferentPDOStatement extends MockPDOStatement {}

/**
 * A different mock database class, for testing multiple connections
 * Mock database class implementing a subset of the PDO API.
 */
class MockDifferentPDO extends MockPDO {

    /**
     * Return a dummy PDO statement
     */
    public function prepare($statement, $driver_options = array()) {
        $this->last_query = new MockDifferentPDOStatement($statement);
        return $this->last_query;
    }
}

/**
 * Models for use during testing
 */
class Simple extends Model { }
class ComplexModelClassName extends Model { }
class ModelWithCustomTable extends Model {
    public static $_table = 'custom_table';
}
class ModelWithCustomTableAndCustomIdColumn extends Model {
    public static $_table = 'custom_table';
    public static $_id_column = 'custom_id_column';
}
class ModelWithFilters extends Model {
    public static function name_is_fred($orm) {
        return $orm->where('name', 'Fred');
    }
    public static function name_is($orm, $name) {
        return $orm->where('name', $name);
    }
}
class ModelWithCustomConnection extends Model {
    const ALTERNATE = 'alternate';
    public static $_connection_name = self::ALTERNATE;
}

class Profile extends Model {
    public function user() {
        return $this->belongs_to('User');
    }
} 
class User extends Model {
    public function profile() {
        return $this->has_one('Profile');
    }
}
class UserTwo extends Model {
    public function profile() {
        return $this->has_one('Profile', 'my_custom_fk_column');
    }
}
class UserFive extends Model {
    public function profile() {
        return $this->has_one('Profile', 'my_custom_fk_column', 'name');
    }
}
class ProfileTwo extends Model {
    public function user() {
        return $this->belongs_to('User', 'custom_user_fk_column');
    }
}
class ProfileThree extends Model {
    public function user() {
        return $this->belongs_to('User', 'custom_user_fk_column', 'name');
    }
}
class Post extends Model { }
class UserThree extends Model {
    public function posts() {
        return $this->has_many('Post');
    }
}
class UserFour extends Model {
    public function posts() {
        return $this->has_many('Post', 'my_custom_fk_column');
    }
}
class UserSix extends Model {
    public function posts() {
        return $this->has_many('Post', 'my_custom_fk_column', 'name');
    }
}
class Author extends Model { }
class AuthorBook extends Model { }
class Book extends Model {
    public function authors() {
        return $this->has_many_through('Author');
    }
}
class BookTwo extends Model {
    public function authors() {
        return $this->has_many_through('Author', 'AuthorBook', 'custom_book_id', 'custom_author_id');
    }
}
class BookThree extends Model {
    public function authors() {
        return $this->has_many_through('Author', 'AuthorBook', 'custom_book_id', 'custom_author_id', 'custom_book_id_in_book_table', 'custom_author_id_in_author_table');
    }
}
class BookFour extends Model {
    public function authors() {
        return $this->has_many_through('Author', 'AuthorBook', 'custom_book_id', 'custom_author_id', null, 'custom_author_id_in_author_table');
    }
}
class BookFive extends Model {
    public function authors() {
        return $this->has_many_through('Author', 'AuthorBook', 'custom_book_id', 'custom_author_id', 'custom_book_id_in_book_table');
    }
}
class MockPrefix_Simple extends Model { } 
class MockPrefix_TableSpecified extends Model {
    public static $_table = 'simple';
} 
