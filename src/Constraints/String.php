<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'Constraint.php';

class StringConstraint extends Constraint {
	
	function check($element, $schema, $myName) {
		if(!is_string($element)) {
			Validator::addError($myName, 'must be a string');
			return;
		}
		
		if (isset($schema->pattern) && !preg_match('/' . $schema->pattern . '/', $element)) {
			Validator::addError($myName, 'does not match the regex pattern '.$schema->pattern);
        }
        
        if(isset($schema->maxLength) && strlen($element) > $schema->maxLength) {
        	Validator::addError($myName, 'must be at most '.$schema->maxLength.' characters long');
        }
        
        if(isset($schema->minLength) && strlen($element) < $schema->minLength) {
        	Validator::addError($myName, 'must be at last '.$schema->minLength.' characters long');
        }
        
        if(isset($schema->enum) && !in_array($element, $schema->enum)) {
        	Validator::addError($myName, 'must have one of the given values: '.join(', ', $schema->enum));
        }
	}	
}