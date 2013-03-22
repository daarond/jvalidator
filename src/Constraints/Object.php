<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'Constraint.php';

class ObjectConstraint extends Constraint {
	
	function check($element, $schema, $myName, $errors) {						
		if(!is_object($element)) {
			$errors[$myName][] = 'must be an object';
			return $errors;
		}
				
		$properties = $schema->properties;
		
		$possibleProps = get_object_vars($properties);
		foreach($possibleProps as $name => $details) {
			$errors = $this->checkProperties($element, $name, $details, 
								   			 $myName.'.'.$name, $errors); 
		}

		if(isset($schema->additionalProperties) && !$schema->additionalProperties) {
			foreach(get_object_vars($element) as $name => $details) {
				if(!array_key_exists($name, $possibleProps)) {
					$errors[$myName.'.'.$name][] = 'this property does not exists in SCHEMA';
				}
			}
		}

		return $errors;
	}
	
	function checkProperties($object, $name, $details, $myName, $errors) {
		$hasProperty = property_exists($object, $name);
		$required = isset($details->required) && $details->required;
		
		if($hasProperty) {
			$errors = Validator::check($object->$name, $details, $myName, $errors);
		} elseif(!$hasProperty && $required) {
			$errors[$myName][] = 'is not defined';
		}
		return $errors;
	}
	
}