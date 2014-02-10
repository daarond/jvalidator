# JValidator
[![Build Status](https://travis-ci.org/brainly/jvalidator.png?branch=master)](https://travis-ci.org/brainly/jvalidator)

JSON Schema validation library for draft v3
- Builds JSON Schemas and checks their syntax
- Validates JSON's against schemas

## Basic usage
======
- Include JValidator in your project
- Define schemas directory and set cache configuration
- Build schema
- Validate!

### Example code

    <?php
    require_once "JValidator.php";

    define("JVALIDATOR_SCHEMA_DIR", JVALIDATOR_ROOT_DIR . "/schemas");
    define("JVALIDATOR_CACHE_DIR",  JVALIDATOR_ROOT_DIR . "/tmp/cache");
    define("JVALIDATOR_USE_CACHE",  false);
  
    try {
      $schema = JValidator\SchemaProvider::getSchema("test.jsonschema");
    } catch (JValidator\SchemaProviderException $e) {
      die("Can not read schema from file. " . $e->getMessage());
    } catch (JValidator\SchemaBuilderException $e) {
      die("Invalid schema. " . $e->getMessage());
    }

    $json = '{"example":true, "int":3, "data":{"items":[]}}';

    JValidator\Validator::validate($json, $schema);

    echo "Validation result: " . JValidator\Validator::getResultCode() . "\n";
    print_r(JValidator\Validator::getValidationErrors());
    
### Analyzing validation results
Following functions can be used to obtain validation results:
- `Validator::getResultCode()` returns: 
  - `0` - validation passed
  - `1` - validation passed, but JSON has been changed (not implemented yet)
  - `2` - JSON is not valid regarding to schema
  - `3` - validation has not been performed yet
- `Validator::getValidationErrors()` returns associative array with errors for each property e.g. `Array ("property" => "error message")`

# About
## Author
Łukasz Lalik for Brainly - lukasz.lalik@brainly.com - https://twitter.com/LukaszLalik  
See also the list of [contributors](/contributors) which participated in this project.

## License
JValidator is licensed under the BSD-3 License - see the `LICENSE` file for details.
