<?php

namespace JValidator;
use \Exception as Exception;

class SchemaProviderException extends Exception {
	const SCHEMA_NOT_FOUND = 1;
	const UNPARSABLE_JSON = 2;
	const BROKEN_CACHE = 3;
	const CACHE_WRITE_ERROR = 4;
}

class InvalidSchemaException extends Exception {
}

class SchemaBuilderException extends Exception {
	const UNKNOWN = 1;
	const NO_EXTEND_FILE = 2;
	const BROKEN_EXTEND = 3;
	const INVALID_TYPE = 4;
	const INVALID_PROPERTY = 5;
	const UNPARSABLE_JSON = 6;
}