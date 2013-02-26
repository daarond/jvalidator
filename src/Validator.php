<?php
namespace JValidator;

require_once 'Constraints/Object.php';
require_once 'Constraints/Array.php';
require_once 'Constraints/String.php';
require_once 'Constraints/Number.php';
require_once 'Constraints/Boolean.php';
require_once 'Constraints/Integer.php';

class Validator {

	// Possible validation results
	const VALID 		= 0;
	const MODIFIED 		= 1;
	const INVALID 		= 2;
	CONST NOT_PERFORMED = 3;

	static private $resultCode = Validator::NOT_PERFORMED;
	static private $validationErrors = array();
	static private $resultJson = "";

	/**
	 * Validates JSON against given schema. Schema should be builded
	 * and checked against syntax errors before.
	 * @param string $json JSON encoded string to validate
	 * @param string $schema JSON encoded schema
	 * @throws InvalidSchemaException
	 */
	static public function validate($json, $schema) {
		self::$validationErrors = array();
		self::$resultCode = Validator::NOT_PERFORMED;
		self::$resultJson = new \stdClass;

		$json = json_decode($json);
		$schema = json_decode($schema);

		if(!is_object($schema)) {
			throw new InvalidSchemaException();
		}

		if(!is_object($json)) {
			self::addError('$', 'Is not a valid JSON');
			return;
		}
		self::$resultCode = Validator::VALID;
		self::check($json, $schema, "$");
		self::$resultJson = json_encode($json);
	}
	
	/**
	 * Validates single property of JSON Schema.
	 * @param stdClass $json Property to validate
	 * @param stdClass $schema Schema for property
	 * @param string $name Name of currently validating property
	 */
	static public function check($json, $schema, $name) {
		switch($schema->type) {
			case 'object':
				$objectConstraint = new Constraint\ObjectConstraint();
				return $objectConstraint->check($json, $schema, $name);
				
			case 'array':
				$arrayConstraint = new Constraint\ArrayConstraint();
				return $arrayConstraint->check($json, $schema, $name);
				
			case 'string':
				$stringConstraint = new Constraint\StringConstraint();
				return $stringConstraint->check($json, $schema, $name);
				
			case 'number':
				$numberConstraint = new Constraint\NumberConstraint();
				return $numberConstraint->check($json, $schema, $name);
				
			case 'boolean':
				$booleanConstraint = new Constraint\BooleanConstraint();
				return $booleanConstraint->check($json, $schema, $name);
				
			case 'integer':
				$integerConstraint = new Constraint\IntegerConstraint();
				return $integerConstraint->check($json, $schema, $name);
				
			default:
				return;
		}	
	}

	/**
	 * Appends new error to errors array.
	 * @param string $property Property that error concerns
	 * @param string $message Content of validation error
	 */
	static public function addError($property, $message) {
		self::$validationErrors[$property] = $message;
		self::$resultCode = Validator::INVALID;
	}

	/**
	 * Returns result code of last performed validation
	 * @return integer Validation code
	 */
	static public function getResultCode() {
		return self::$resultCode;
	}

	/**
	 * Returns array of errors from last performed validation
	 * @return array Validation errors
	 */
	static public function getValidationErrors() {
		return self::$validationErrors;
	}

	/**
	 * Returns result JSON after validation performed on it
	 * @return string JSON
	 */
	static public function getResultJson() {
		return self::$resultJson;
	}
}