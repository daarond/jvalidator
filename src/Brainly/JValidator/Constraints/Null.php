<?php

namespace Brainly\JValidator\Constraints;

use Brainly\JValidator\Validator;
use Brainly\JValidator\Constraints\Constraint;

class NullConstraint extends Constraint {
	function check($element, $schema, $myName, $errors) {
		if(!is_null($element)) {
			$errors[$myName][] = 'must be null';
		}
		return $errors;
	}
}
