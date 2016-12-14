<?php

class MagicMethodsTest extends PHPUnit_Framework_TestCase {

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

    public function testMagicMethodUnset() {
        $model = Model::factory("Simple")->create();
        $model->property = "test";
        unset($model->property);
        $this->assertFalse(isset($model->property));
        $this->assertTrue($model->get("property")!="test");
    }
}
