<?php

namespace JValidator\Constraint;
use JValidator\Validator;

require_once 'constraint.php';

class ArrayConstraint extends Constraint {
	
	function check($element, $schema, $myName) {		
		if(!is_array($element)) {
			Validator::addError($myName, 'is not an array');
			return;
		}
				
		$itemsSchema = $schema->items;
		
		if(isset($schema->minItems)) {
			$minItems = $schema->minItems;
		} else {
			$minItems = 0;
		}

		if(isset($schema->maxItems)) {
			$maxItems = $schema->maxItems;
		} else {
			$maxItems = PHP_INT_MAX;
		}
		
		if(count($element) < $minItems) {
			Validator::addError($myName, 'must have at last '.$minItems.' items');
			return;
		}

		if(count($element) > $maxItems) {
			Validator::addError($myName, 'must have at most '.$maxItems.' items');
			return;
		}
		
		$i = 0;
		foreach($element as $item) {
			$this->checkArrayItems($item, $itemsSchema, $myName.'.'.$i);
			$i++;
		}		
	}
	
	function checkArrayItems($item, $schema, $myName) {
		Validator::check($item, $schema, $myName);
	}
	
}