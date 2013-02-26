<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'Constraint.php';

class BooleanConstraint extends Constraint {
	
	function check($element, $schema, $myName) {		
		if(!is_bool($element)) {
			Validator::addError($myName, 'must be a boolean');
		}
	}	
}