<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'constraint.php';

class IntegerConstraint extends Constraint {
	
	function check($element, $schema, $myName) {		
		if(!is_int($element)) {
			Validator::addError($myName, 'must be an integer');
			return;
		}
		
		if(isset($schema->minimum)) {
			if($element < $schema->minimum) {
				Validator::addError($myName, 'must be greater than '.$schema->minimum);
			}
		}
		
		if(isset($schema->maximum)) {
			if($element > $schema->maximum) {
				Validator::addError($myName, 'must be less than '.$schema->maximum);
			}
		}
		
		if(isset($schema->enum) && !in_array($element, $schema->enum)) {
			Validator::addError($myName, 'must have one of the given values: '.join(', ', $schema->enum));
        }
	}	
}