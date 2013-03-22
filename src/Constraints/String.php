<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'Constraint.php';

class StringConstraint extends Constraint {
	
	function check($element, $schema, $myName, $errors) {
        	if(!is_string($element)) {
        		$errors[$myName][] = 'must be a string';
        		return $errors;
        	}
        	
        	if (isset($schema->pattern) && !preg_match('/' . $schema->pattern . '/', $element)) {
        		$errors[$myName][] = 'does not match the regex pattern '.$schema->pattern;
                }
                
                if(isset($schema->maxLength) && strlen($element) > $schema->maxLength) {
                	$errors[$myName][] = 'must be at most '.$schema->maxLength.' characters long';
                }
                
                if(isset($schema->minLength) && strlen($element) < $schema->minLength) {
                	$errors[$myName][] = 'must be at last '.$schema->minLength.' characters long';
                }
                
                if(isset($schema->enum) && !in_array($element, $schema->enum)) {
                	$errors[$myName][] = 'must have one of the given values: '.join(', ', $schema->enum);
                }

                return $errors;
        	}	
        }