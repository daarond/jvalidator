<?php

namespace JValidator\Constraint;
use JValidator\Validator;

class UnionConstraint extends Constraint {
	function check($element, $schema, $myName, $errors) {
		$types = $schema->type;
		$allResults[$myName] = array();

		foreach($types as $t) {
			$schema->type = $t;
			$result = Validator::check($element, $schema, $myName, array());
			if(!count($result)) {
				$schema->type = $types;
				return $errors;
			}
			$allResults[$myName][$t] = $result[$myName];
		}

		$schema->type = $types;
		return array_merge($errors, $allResults);
	}
}