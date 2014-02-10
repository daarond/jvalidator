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
}
