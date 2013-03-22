<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'Constraint.php';

class NumberConstraint extends Constraint {
	
	function check($element, $schema, $myName, $errors) {		
		if(!is_numeric($element) || is_string($element)) {
			$errors[$myName][] = 'must be a number';
			return $errors;
		}
		
		if(isset($schema->minimum)) {
			if($element < $schema->minimum) {
				$errors[$myName][] = 'must be greater than '.$schema->minimum;
			}
		}
		
		if(isset($schema->maximum)) {
			if($element > $schema->maximum) {
				$errors[$myName][] = 'must be less than '.$schema->maximum;
			}
		}
		
		if(isset($schema->enum) && !in_array($element, $schema->enum)) {
        	$errors[$myName][] = 'must have one of the given values: '.join(', ', $schema->enum);
        }

        return $errors;
	}	
}