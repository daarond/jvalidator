<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'constraint.php';

class ObjectConstraint extends Constraint {
	
	function check($element, $schema, $myName) {						
		if(!is_object($element)) {
			Validator::addError($myName, 'must be an object');
			return;
		}
				
		$properties = $schema->properties;
		
		$possibleProps = get_object_vars($properties);
		foreach($possibleProps as $name => $details) {
			$this->checkProperties($element, $name, $details, 
								   $myName.'.'.$name); 
		}

		if(isset($schema->additionalProperties) && !$schema->additionalProperties) {
			foreach(get_object_vars($element) as $name => $details) {
				if(!array_key_exists($name, $possibleProps)) {
					Validator::addError($myName.'.'.$name, 'this property does not exists in SCHEMA');
				}
			}
		}
	}
	
	function checkProperties($object, $name, $details, $myName) {
		$hasProperty = property_exists($object, $name);
		$required = isset($details->required) && $details->required;
		
		if($hasProperty) {
			Validator::check($object->$name, $details, $myName);
		} elseif(!$hasProperty && $required) {
			Validator::addError($myName, 'is not defined');
		}
	}
	
}