<?php

/*
 * This file is part of the JValidator library.
 *
 * (c) Łukasz Lalik <lukasz.lalik@brainly.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brainly\JValidator;

/**
 * Specification of fields that can be used in schemas.
 */
class SchemaSpec
{
    public static function getAllowedTypes()
    {
        return [
            "string",
            "number",
            "integer",
            "boolean",
            "object",
            "array",
            "any",
            "null"
        ];
    }

    public static function getAllowedProperties($type)
    {
        $allowedProperties = [
                "*" => [
                    "type",
                    "required",
                    "id",
                    "value"
                ],
                "string" => [
                    "pattern",
                    "minLength",
                    "maxLength",
                    "enum",
                    "description",
                    "extends",
                    "id",
                    "format",
                    "default"
                ],
                "number" => [
                    "minimum",
                    "maximum",
                    "enum",
                    "description",
                    "extends",
                    "id",
                    "default"
                ],
                "integer" => [
                    "minimum",
                    "maximum",
                    "enum",
                    "description",
                    "extends",
                    "id",
                    "default"
                ],
                "boolean" => [
                    "description",
                    "extends",
                    "id",
                    "default"
                ],
                "object" => [
                    "properties",
                    "additionalProperties",
                    "description",
                    "extends",
                    "id",
                    "default"
                ],
                "array" => [
                    "items",
                    "minItems",
                    "maxItems",
                    "description",
                    "extends",
                    "id",
                    "uniqueItems",
                    "default"
                ],
                "any" => [
                    "description",
                    "extends",
                    "id",
                    "properties"
                ],
                "null" => []
            ];

        return array_merge($allowedProperties[$type], $allowedProperties["*"]);
    }

    public static function getDefault($key)
    {
        $defaults = ["type" => "any"];
        return $defaults[$key];
    }
}
