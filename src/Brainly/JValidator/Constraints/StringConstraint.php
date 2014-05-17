<?php

/*
 * This file is part of the JValidator library.
 *
 * (c) Łukasz Lalik <lukasz.lalik@brainly.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brainly\JValidator\Constraints;

use Brainly\JValidator\Validator;
use Brainly\JValidator\Constraints\Constraint;

/**
 * Constraint for validating strings
 */
class StringConstraint implements Constraint
{
    /**
     * Performs validation of given element.
     *
     * @param Validator $validator  Instance of Validator to perform recursive validation
     * @param mixed     $element    Element to validate against schema
     * @param object    $schema     Part of Schema that validates given element
     * @param string    $myName     Name of validated element
     * @param array     $errors     Array of currently gathered errors
     * @return array    Currently gathered errors
     */
    public function check(Validator $validator, &$element, $schema, $myName, array $errors)
    {
        if (!is_string($element)) {
            if ($validator->coerce && isset($schema->default)) {
                $element = $schema->default;
                return $errors;
            } else {
                $errors[$myName][] = 'must be a string';
                return $errors;
            }
        }

        if (isset($schema->pattern) && !preg_match('/' . $schema->pattern . '/', $element)) {

            if ($validator->coerce && isset($schema->default)) {
                $element = $schema->default;
                return $errors;
            }

            $errors[$myName][] = 'does not match the regex pattern '.$schema->pattern;
        }
        
        if (isset($schema->maxLength) && strlen($element) > $schema->maxLength) {

            if ($validator->coerce){
                $element = substr($element, 0, $schema->maxLength);
                return $errors;
            }

            $errors[$myName][] = 'must be at most '.$schema->maxLength.' characters long';
        }
        
        if (isset($schema->minLength) && strlen($element) < $schema->minLength) {

            if ($validator->coerce && isset($schema->default)) {
                $element = $schema->default;
                return $errors;
            }

            $errors[$myName][] = 'must be at least '.$schema->minLength.' characters long';
        }
        
        if (isset($schema->enum) && !in_array($element, $schema->enum)) {

            if ($validator->coerce && isset($schema->default)) {
                $element = $schema->default;
                return $errors;
            }

            $errors[$myName][] = 'must have one of the given values: '.join(', ', $schema->enum);
        }

        return $errors;
    }
}
