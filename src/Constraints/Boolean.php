<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'Constraint.php';

class BooleanConstraint extends Constraint {
	
	function check($element, $schema, $myName, $errors) {
		if(!is_bool($element)) {
			$errors[$myName][] = 'must be a boolean';
		}
		return $errors;
	}	
}