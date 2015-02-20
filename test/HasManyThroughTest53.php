<?php
namespace
{
        class HasManyThroughTest extends PHPUnit_Framework_TestCase
        {
                private $sql = '
                        CREATE TABLE post (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                title TEXT
                        );

                        CREATE TABLE tag (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                name TEXT
                        );

                        CREATE TABLE post_tag (
                                post_id INTEGER,
                                tag_id INTEGER,

                                FOREIGN KEY(post_id) REFERENCES post(id),
                                FOREIGN KEY(tag_id) REFERENCES tag(id)
                        );

                        INSERT INTO post (title)
                        VALUES ("A Blog Post Title: PHPUnit Testing");

                        INSERT INTO tag (name) VALUES ("php");
                        INSERT INTO tag (name) VALUES ("programming");
                        INSERT INTO tag (name) VALUES ("github");

                        INSERT INTO post_tag (post_id, tag_id) VALUES (1, 1);
                        INSERT INTO post_tag (post_id, tag_id) VALUES (1, 2);
                        INSERT INTO post_tag (post_id, tag_id) VALUES (1, 3);
                ';

                public function setUp () {
                        $db_handle = new PDO('sqlite::memory:');
                        $db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                        $db_handle->exec($this->sql);
                        
                        ORM::set_db($db_handle);
                        ORM::configure('logging', true);
                }

                public function tearDown ()
                {
                        ORM::configure('logging', false);
                        ORM::set_db(null);
                }

                public function testHasManyThrough () {
                        $video = \PHPProject\Models\Post::find_one(1);
                        $tags = $video->tags()->find_many();
                        $this->assertArrayHasKey('id', $tags[0]->as_array());
                        $this->assertArrayHasKey('name', $tags[0]->as_array());
                }
        }
}

// We need to use the namespaces here to test whether
// the table names are being correctly generated when
// using $_table_use_short_name = true;
namespace PHPProject\Models
{
        class Post extends \Model
        {
                public static $_table_use_short_name = true;

                public function tags ()
                {
                        return $this->has_many_through('\\PHPProject\\Models\\Tag');
                }
        }
        class Tag extends \Model
        {
                public static $_table_use_short_name = true;

                public function posts ()
                {
                        return $this->has_many_through('\\PHPProject\\Models\\Post');
                }
        }
        class PostTag extends \Model
        {
                public static $_table_use_short_name = true;
        }
}
?>