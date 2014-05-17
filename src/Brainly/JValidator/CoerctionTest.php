<?php

namespace Brainly\JValidator;

set_include_path(get_include_path().PATH_SEPARATOR.'Constraints');
require_once('Constraint.php');
require_once('ArrayConstraint.php');

require_once('Validator.php');


class CoerctionTest extends \PHPUnit_Framework_TestCase {

    protected $validator;

    protected function setUp() {
        $this->validator = new Validator();
        $this->validator->coerce = true;
    }

    private function create_array(){
        $data = new \StdClass();
        $data->type = 'array';
        $items = new \StdClass();
        $items->required = true;
        $items->type = 'integer';
        $items->default = array(1,2,3);
        $data->items = $items;
        return json_encode($data);
    }

    public function testArrayNotArray(){

        $data = new \StdClass();
        $data->array = 10;
        $json = json_encode($data);

        $schema = $this->create_array();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_array($this->validator->resultObject), "not coerced into array");
        $this->assertEquals(3, count($this->validator->resultObject), "not 3 items");
    }

    public function testArrayBelowMinimum(){

        $data = new \StdClass();
        $data->array = array();
        $json = json_encode($data);

        $schema = $this->create_array();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertEquals(3, count($this->validator->resultObject), "not 3 items");
    }

    public function testArrayAboveMaximum(){

        $data = new \StdClass();
        $data->array = array(1,2,3,4,5,6,7);
        $json = json_encode($data);

        $schema = $this->create_array();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertEquals(3, count($this->validator->resultObject), "not 3 items");
    }

    public function testArrayUnique(){

        $data = new \StdClass();
        $data->array = array(1,2,3,3,3);
        $json = json_encode($data);

        $schema = $this->create_array();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertEquals(3, count($this->validator->resultObject), "not 3 items");
    }
}
 