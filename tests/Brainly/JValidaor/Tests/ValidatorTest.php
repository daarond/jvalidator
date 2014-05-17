<?php

namespace Brainly\JValidator\Tests;

use Brainly\JValidator\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testValidateValidTypes()
    {
        $schema = file_get_contents(__DIR__ . '/Jsons/test_schema_1.jsonschema');
        $json   = file_get_contents(__DIR__ . '/Jsons/valid_json_1.json');

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, $this->validator->getResultCode());
        $this->assertEquals([], $this->validator->getValidationErrors());
    }

    public function testValidateInvalidTypes()
    {
        $schema = file_get_contents(__DIR__ . '/Jsons/test_schema_1.jsonschema');
        $json   = file_get_contents(__DIR__ . '/Jsons/invalid_json_1.json');

        $this->validator->validate($json, $schema);

        $this->assertEquals(2, $this->validator->getResultCode());
        $this->assertNotEquals([], $this->validator->getValidationErrors());
    }

    public function testValidateValidArray()
    {
        $schema = file_get_contents(__DIR__ . '/Jsons/test_schema_5.jsonschema');
        $json   = file_get_contents(__DIR__ . '/Jsons/valid_json_2.json');

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, $this->validator->getResultCode());
        $this->assertEquals([], $this->validator->getValidationErrors());
    }

    public function testValidateInvalidArray()
    {
        $schema = file_get_contents(__DIR__ . '/Jsons/test_schema_5.jsonschema');
        $json   = file_get_contents(__DIR__ . '/Jsons/invalid_json_2.json');

        $this->validator->validate($json, $schema);

        $this->assertEquals(2, $this->validator->getResultCode());
        $this->assertNotEquals([], $this->validator->getValidationErrors());

        $json   = file_get_contents(__DIR__ . '/Jsons/invalid_json_3.json');

        $this->validator->validate($json, $schema);

        $this->assertEquals(2, $this->validator->getResultCode());
        $this->assertNotEquals([], $this->validator->getValidationErrors());
    }

    private function create_array(){

        $data = new \stdClass();
        $data->type = 'array';
        $items = new \stdClass();
        $items->required = true;
        $items->type = 'integer';
        $items->default = array(1,2,3);
        $data->items = $items;

        $property = new \stdClass();
        $property->array = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return $top;
    }

    public function testArraySuccess(){

        $data = new \stdClass();
        $data->array = array(2,3);
        $json = json_encode($data);

        $schema = $this->create_array();
        $schema->properties->array->minItems = 1;
        $schema->properties->array->maxItems = 3;
        $schema->properties->array->uniqueItems = true;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, count($this->validator->getValidationErrors()), "array test not successful");
    }

    public function testCoerceArrayNotArray(){

        $data = new \stdClass();
        $data->array = 10;
        $json = json_encode($data);

        $schema = $this->create_array();
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_array($this->validator->resultObject->array), "not coerced into array");
        $this->assertEquals(3, count($this->validator->resultObject->array), "not 3 items");
    }

    public function testCoerceArrayBelowMinimum(){

        $data = new \stdClass();
        $data->array = array();
        $json = json_encode($data);

        $schema = $this->create_array();
        $schema->properties->array->minItems = 3;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertEquals(3, count($this->validator->resultObject->array), "not 3 items");
    }

    public function testCoerceArrayAboveMaximum(){

        $data = new \stdClass();
        $data->array = array(1,2,3,4,5,6,7);
        $json = json_encode($data);

        $schema = $this->create_array();
        $schema->properties->array->maxItems = 3;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertEquals(3, count($this->validator->resultObject->array), "not 3 items");
    }

    public function testCoerceArrayUnique(){

        $data = new \stdClass();
        $data->array = array(1,2,3,3,3);
        $json = json_encode($data);

        $schema = $this->create_array();
        $schema->properties->array->uniqueItems = true;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertEquals(3, count($this->validator->resultObject->array), "not 3 items");
    }


    private function create_boolean(){

        $data = new \stdClass();
        $data->type = 'boolean';
        $data->required = true;
        $data->default = true;

        $property = new \stdClass();
        $property->boolean = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return $top;
    }

    public function testBooleanSuccess(){

        $data = new \stdClass();
        $data->boolean = true;
        $json = json_encode($data);

        $schema = $this->create_boolean();
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, count($this->validator->getValidationErrors()), "boolean test not successful");
        $this->assertTrue($this->validator->resultObject->boolean, "value not set properly");
    }

    public function testCoerceBoolNotBool(){

        $data = new \stdClass();
        $data->boolean = 10;
        $json = json_encode($data);

        $schema = $this->create_boolean();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_bool($this->validator->resultObject->boolean), "not coerced into boolean");
        $this->assertTrue($this->validator->resultObject->boolean, "not true");
    }

    private function create_integer(){

        $data = new \stdClass();
        $data->type = 'integer';
        $data->default = 9;
        $data->required = true;

        $property = new \stdClass();
        $property->integer = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return $top;
    }

    public function testIntegerSuccess(){

        $data = new \stdClass();
        $data->integer = 7;
        $json = json_encode($data);

        $schema = $this->create_integer(false);
        $schema->properties->integer->minimum = 3;
        $schema->properties->integer->maximum = 12;
        $schema->properties->integer->enum = array(7,8,9,10);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, count($this->validator->getValidationErrors()), "integer test not successful");
        $this->assertEquals(7, $this->validator->resultObject->integer, "value not set properly");
    }

    public function testCoerceIntNotInt(){

        $data = new \stdClass();
        $data->integer = 'abc';
        $json = json_encode($data);

        $schema = $this->create_integer(false);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->integer), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->integer, "not matching default");
    }

    public function testCoerceIntBelowMinimum(){

        $data = new \stdClass();
        $data->integer = 1;
        $json = json_encode($data);

        $schema = $this->create_integer(false);
        $schema->properties->integer->minimum = 3;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->integer), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->integer, "not matching default");
    }

    public function testCoerceIntAboveMaximum(){

        $data = new \stdClass();
        $data->integer = 100;
        $json = json_encode($data);

        $schema = $this->create_integer(false);
        $schema->properties->integer->maximum = 12;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->integer), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->integer, "not matching default");
    }

    public function testCoerceIntEnum(){

        $data = new \stdClass();
        $data->integer = 100;
        $json = json_encode($data);

        $schema = $this->create_integer();
        $schema->properties->integer->enum = array(7,8,9,10);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->integer), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->integer, "not matching default");
    }

    private function create_null(){

        $data = new \stdClass();
        $data->type = 'null';
        $data->required = true;

        $property = new \stdClass();
        $property->null = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return $top;
    }

    public function testNullSuccess(){

        $data = new \stdClass();
        $data->null = null;
        $json = json_encode($data);

        $schema = $this->create_null();
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, count($this->validator->getValidationErrors()), "number test not successful");
        $this->assertNull($this->validator->resultObject->null, "value not set properly");
    }

    public function testCoerceNullNotNull(){

        $data = new \stdClass();
        $data->null = 10;
        $json = json_encode($data);

        $schema = $this->create_null();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertNull($this->validator->resultObject->null, "not null");
    }

    private function create_number(){

        $data = new \stdClass();
        $data->type = 'number';
        $data->default = 9;
        $data->required = true;

        $property = new \stdClass();
        $property->number = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return $top;
    }

    public function testNumberSuccess(){

        $data = new \stdClass();
        $data->number = 7;
        $json = json_encode($data);

        $schema = $this->create_number();
        $schema->properties->number->minimum = 3;
        $schema->properties->number->maximum = 12;
        $schema->properties->number->enum = array(7,8,9,10);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, count($this->validator->getValidationErrors()), "number test not successful");
        $this->assertEquals(7, $this->validator->resultObject->number, "value not set properly");
    }

    public function testCoerceNumberNotNumber(){

        $data = new \stdClass();
        $data->number = 'abc';
        $json = json_encode($data);

        $schema = $this->create_number(false);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->number), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->number, "not matching default");
    }

    public function testCoerceNumberBelowMinimum(){

        $data = new \stdClass();
        $data->number = 1;
        $json = json_encode($data);

        $schema = $this->create_number(false);
        $schema->properties->number->minimum = 3;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->number), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->number, "not matching default");
    }

    public function testCoerceNumberAboveMaximum(){

        $data = new \stdClass();
        $data->number = 100;
        $json = json_encode($data);

        $schema = $this->create_number(false);
        $schema->properties->number->maximum = 12;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->number), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->number, "not matching default");
    }

    public function testCoerceNumberEnum(){

        $data = new \stdClass();
        $data->number = 100;
        $json = json_encode($data);

        $schema = $this->create_number(false);
        $schema->properties->number->enum = array(7,8,9,10);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_int($this->validator->resultObject->number), "not coerced into integer");
        $this->assertEquals(9, $this->validator->resultObject->number, "not matching default");
    }

    private function create_object(){

        $data = new \stdClass();
        $data->type = 'object';
        $data->required = true;
        $data->default = new \stdClass();

        $property = new \stdClass();
        $property->object = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return json_encode($top);
    }

    public function testCoerceObjectNotObject(){

        $data = new \stdClass();
        $data->object = 10;
        $json = json_encode($data);

        $schema = $this->create_object();

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_object($this->validator->resultObject->object), "not coerced into object");

    }

    private function create_string(){

        $data = new \stdClass();
        $data->type = 'string';
        $data->default = 'abc';
        $data->required = true;

        $property = new \stdClass();
        $property->string = $data;

        $top = new \stdClass();
        $top->type = 'object';
        $top->required = true;
        $top->properties = $property;

        return $top;
    }

    public function testStringSuccess(){

        $data = new \stdClass();
        $data->string = 'abc';
        $json = json_encode($data);

        $schema = $this->create_string();
        $schema->properties->string->pattern = 'a.c';
        $schema->properties->string->minLength = 1;
        $schema->properties->string->maxLength = 4;
        $schema->properties->string->enum = array('def','abc','xyz');
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(0, count($this->validator->getValidationErrors()), "integer test not successful");
        $this->assertEquals('abc', $this->validator->resultObject->string, "value not set properly");
    }

    public function testCoerceStringNotString(){

        $data = new \stdClass();
        $data->string = 1;
        $json = json_encode($data);

        $schema = $this->create_string(false);
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_string($this->validator->resultObject->string), "not coerced into string");
        $this->assertEquals('abc', $this->validator->resultObject->string, "not matching default");
    }

    public function testCoerceStringPattern(){

        $data = new \stdClass();
        $data->string = 1;
        $json = json_encode($data);

        $schema = $this->create_string(false);
        $schema->properties->string->pattern = 'a.c';
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_string($this->validator->resultObject->string), "not coerced into string");
        $this->assertEquals('abc', $this->validator->resultObject->string, "not matching default");
    }

    public function testCoerceStringTooLong(){

        $data = new \stdClass();
        $data->string = 'abcdefg';
        $json = json_encode($data);

        $schema = $this->create_string(false);
        $schema->properties->string->maxLength = 3;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_string($this->validator->resultObject->string), "not coerced into string");
        $this->assertEquals('abc', $this->validator->resultObject->string, "not matching default");
    }

    public function testCoerceStringTooShort(){

        $data = new \stdClass();
        $data->string = '';
        $json = json_encode($data);

        $schema = $this->create_string(false);
        $schema->properties->string->minLength = 2;
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_string($this->validator->resultObject->string), "not coerced into string");
        $this->assertEquals('abc', $this->validator->resultObject->string, "not matching default");
    }

    public function testCoerceStringEnum(){

        $data = new \stdClass();
        $data->string = '';
        $json = json_encode($data);

        $schema = $this->create_string(false);
        $schema->properties->string->enum = array('def','abc','xyz');
        $schema = json_encode($schema);

        $this->validator->validate($json, $schema);

        $this->assertEquals(Validator::MODIFIED, $this->validator->resultCode, "not set to modified");
        $this->assertTrue(is_string($this->validator->resultObject->string), "not coerced into string");
        $this->assertEquals('abc', $this->validator->resultObject->string, "not matching default");
    }
}
