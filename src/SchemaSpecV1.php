<?php
namespace JValidator;

class SchemaSpecV1 {
	public static function getAllowedTypes() {
		return array("string", "number", "integer", "boolean",
			  "object", "array", "any");
	}

	public static function getAllowedProperties($type) {
		$allowedProperties = array(
				"*" => 
					array("type", "required", "id", "value"),
				"string" => 
					array("pattern", "minLength", "maxLength", "enum",
						  "description", "extends", "id"),
				"number" => 
					array("minimum", "maximum", "enum", "description",
						  "extends", "id"),
				"integer" => 
					array("minimum", "maximum", "enum", "description",
						  "extends", "id"),
				"boolean" => 
					array("description", "extends", "id"),
				"object" => 
					array("properties", "additional_properties", 
						  "description", "extends", "id"),
				"array" => 
					array("items", "minItems", "maxItems", "description",
						  "extends", "id"),
				"any" => 
					array("description", "extends", "id"),
			);
		return array_merge($allowedProperties[$type], $allowedProperties["*"]);
	}

	public static function getDefault($key) {
		$defaults = array(
				"type" => "any"
			);
		return $defaults[$key];
	}
}