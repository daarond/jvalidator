<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'constraint.php';

class NumberConstraint extends Constraint {
	
	function check($element, $schema, $myName) {		
		if(!is_numeric($element) || is_string($element)) {
			Validator::addError($myName, 'must be a number');
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