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
 * Constraint for validating booleans
 */
class BooleanConstraint implements Constraint
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
        if (!is_bool($element)) {
            if ($validator->coerce && isset($schema->default)) {
                $element = $schema->default;
                $validator->resultCode = Validator::MODIFIED;
                return $errors;
            }
            $errors[$myName][] = 'must be a boolean';
        }
        return $errors;
    }
}
