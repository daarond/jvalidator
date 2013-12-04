<?php
namespace Brainly\JValidator;

use Brainly\JValidator\Constraints\ObjectConstraint;
use Brainly\JValidator\Constraints\ArrayConstraint;
use Brainly\JValidator\Constraints\StringConstraint;
use Brainly\JValidator\Constraints\NumberConstraint;
use Brainly\JValidator\Constraints\BooleanConstraint;
use Brainly\JValidator\Constraints\IntegerConstraint;
use Brainly\JValidator\Constraints\NullConstraint;
use Brainly\JValidator\Constraints\UnionConstraint;

class Validator {

	// Possible validation results
	const VALID 		= 0;
	const MODIFIED 		= 1;
	const INVALID 		= 2;
	const NOT_PERFORMED = 3;

	static private $allowAdditionalFields = false;

	static private $resultCode = Validator::NOT_PERFORMED;
	static private $validationErrors = array();
	static private $resultJson = "";

	static public function setAllowAdditionalFields($allow) {
		self::$allowAdditionalFields = $allow;
	}

	static public function allowAdditionalFields() {
		return self::$allowAdditionalFields;
	}

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

		$errors = self::check($json, $schema, "$", array());
		self::setErrors($errors);

		self::$resultJson = json_encode($json);
	}
	
	/**
	 * Validates single property of JSON Schema.
	 * @param stdClass $json Property to validate
	 * @param stdClass $schema Schema for property
	 * @param string $name Name of currently validating property
	 */
	static public function check($json, $schema, $name, $errors) {
		if(is_array($schema->type)) {
			$unionConstraint = new Constraint\UnionConstraint();
			return $unionConstraint->check($json, $schema, $name, $errors);
		} else {
			switch($schema->type) {
				case 'object':
					$objectConstraint = new ObjectConstraint();
					return $objectConstraint->check($json, $schema, $name, $errors);
					
				case 'array':
					$arrayConstraint = new ArrayConstraint();
					return $arrayConstraint->check($json, $schema, $name, $errors);
					
				case 'string':
					$stringConstraint = new StringConstraint();
					return $stringConstraint->check($json, $schema, $name, $errors);
					
				case 'number':
					$numberConstraint = new NumberConstraint();
					return $numberConstraint->check($json, $schema, $name, $errors);
					
				case 'boolean':
					$booleanConstraint = new BooleanConstraint();
					return $booleanConstraint->check($json, $schema, $name, $errors);
					
				case 'integer':
					$integerConstraint = new IntegerConstraint();
					return $integerConstraint->check($json, $schema, $name, $errors);

				case 'null':
					$nullConstraint = new NullConstraint();
					return $nullConstraint->check($json, $schema, $name, $errors);
					
				default:
					return $errors;
			}
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

	static public function setErrors($errors) {
		self::$validationErrors = $errors;
		if(count($errors)) {
			self::$resultCode = Validator::INVALID;		
		}
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
