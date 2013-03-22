<?php

namespace JValidator\Constraint;
use JValidator\Validator;

class NullConstraint extends Constraint {
	function check($element, $schema, $myName, $errors) {
		if(!is_null($element)) {
			$errors[$myName][] = 'must be null';
		}
		return $errors;
	}
}