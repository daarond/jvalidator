<?php

namespace Brainly\JValidator\Constraints;

use Brainly\JValidator\Validator;
use Brainly\JValidator\Constraints\Constraint;

class IntegerConstraint extends Constraint {
	
	function check($element, $schema, $myName, $errors) {		
		if(!is_int($element)) {
			$errors[$myName][] = 'must be an integer';
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
