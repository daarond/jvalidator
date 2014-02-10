<?php

namespace Brainly\JValidator\Tests;

use Brainly\JValidator\SchemaProvider;

class SchemaProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected function setUp()
    {
        $this->provider = new SchemaProvider(__DIR__ . '/Jsons');
    }

    public function testGetValidSchemaWithoutExtends()
    {
        $expected = file_get_contents(__DIR__ . '/Jsons/test_schema_4.jsonschema');

        $schema = $this->provider->getSchema('test_schema_4.jsonschema');

        $this->assertEquals(
            json_decode($expected, true),
            json_decode($schema, true)
        );
    }

    public function testGetValidSchemaWithExtends()
    {
        $expected = file_get_contents(__DIR__ . '/Jsons/test_schema_4.jsonschema');

        $schema = $this->provider->getSchema('test_schema_2.jsonschema');

        $this->assertEquals(
            json_decode($expected, true),
            json_decode($schema, true)
        );
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\InvalidSchemaException
     * @expectedExceptionMessage is not allowed
     */
    public function testGetInvalidSchemaWithoutExtends()
    {
        $schema = $this->provider->getSchema('invalid_schema_1.jsonschema');
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\InvalidSchemaException
     * @expectedExceptionMessage is not allowed
     */
    public function testGetSchemaWithInvalidType()
    {
        $schema = $this->provider->getSchema('invalid_schema_5.jsonschema');
    }


    /**
     * @expectedException Brainly\JValidator\Exceptions\SchemaProviderException
     * @expectedExceptionMessage Unable to decode file
     */
    public function testGetNoJsonAsSchema()
    {
        $schema = $this->provider->getSchema('no_json.json');
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\SchemaProviderException
     * @expectedExceptionMessage not found
     */
    public function testGetMissingSchema()
    {
        $schema = $this->provider->getSchema('missing.jsonschema');
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\SchemaProviderException
     * @expectedExceptionMessage not found
     */
    public function testGetSchemaWithMissingExtends()
    {
        $schema = $this->provider->getSchema('invalid_schema_2.jsonschema');
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\InvalidSchemaException
     * @expectedExceptionMessage is not allowed
     */
    public function testBuildInvalidExtends()
    {
        $schema = $this->provider->getSchema('invalid_schema_3.jsonschema');
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\SchemaProviderException
     * @expectedExceptionMessage Unable to decode file
     */
    public function testBuildNoJsonExtends()
    {
        $schema = $this->provider->getSchema('invalid_schema_4.jsonschema');
    }

    public function testBuildValidSchemaWithUnion()
    {
        $schema = $this->provider->getSchema('test_schema_1.jsonschema');
        $expected = file_get_contents(__DIR__ . '/Jsons/test_schema_1.jsonschema');
        $this->assertEquals(
            json_decode($expected, true),
            json_decode($schema, true)
        );
    }

    /**
     * @expectedException Brainly\JValidator\Exceptions\InvalidSchemaException
     * @expectedExceptionMessage is not allowed
     */
    public function testBuildInvalidSchemaWithUnion()
    {
        $schema = $this->provider->getSchema('invalid_union_schema.jsonschema');
    }
}
