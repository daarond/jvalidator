<?php

namespace JValidator;

require_once 'constraints/object.php';
require_once 'constraints/array.php';
require_once 'constraints/string.php';
require_once 'constraints/number.php';
require_once 'constraints/boolean.php';
require_once 'constraints/integer.php';

class Validator {

	// Possible results
	const VALID 	= 0;
	const MODIFIED 	= 1;
	const INVALID 	= 2;

	static public $resultCode;
	static public $validationErrors;
	static public $resultJson;

	static public function validate($json, $schema) {
		// Initialization, cleanup
		self::$validationErrors = array();
		self::$resultCode = Validator::VALID;
		self::$resultJson = new \stdClass;

		if(!is_object($schema)) {
			throw new InvalidSchemaException();
		}

		if(!is_object($json)) {
			self::addError('$', 'Is not a valid JSON');
			return;
		}

		self::check($json, $schema, "$");

		self::$resultJson = $json;		
	}
	
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

	static public function addError($property, $message) {
		self::$validationErrors[$property] = $message;
		self::$resultCode = Validator::INVALID;
	}
}