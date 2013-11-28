<?php

namespace Brainly\JValidator\Constraints;

use Brainly\JValidator\Validator;
use Brainly\JValidator\Constraints\Constraint;

class BooleanConstraint extends Constraint {
	
	function check($element, $schema, $myName, $errors) {
		if(!is_bool($element)) {
			$errors[$myName][] = 'must be a boolean';
		}
		return $errors;
	}	
}
