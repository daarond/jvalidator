<?php

namespace Brainly\JValidator\Tests;

use Brainly\JValidator\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateValidTypes()
    {
        $schema = file_get_contents(__DIR__ . '/Jsons/test_schema_1.jsonschema');
        $json   = file_get_contents(__DIR__ . '/Jsons/valid_json_1.json');

        Validator::validate($json, $schema);

        $this->assertEquals(0, Validator::getResultCode());
        $this->assertEquals([], Validator::getValidationErrors());
    }

    public function testValidateInvalidTypes()
    {
        $schema = file_get_contents(__DIR__ . '/Jsons/test_schema_1.jsonschema');
        $json   = file_get_contents(__DIR__ . '/Jsons/invalid_json_1.json');

        Validator::validate($json, $schema);

        $this->assertEquals(2, Validator::getResultCode());
        $this->assertNotEquals([], Validator::getValidationErrors());
    }
}
