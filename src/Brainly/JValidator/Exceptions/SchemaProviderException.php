<?php
namespace Brainly\JValidator\Exceptions;

class SchemaProviderException extends \Exception {
    const SCHEMA_NOT_FOUND = 1;
    const UNPARSABLE_JSON = 2;
    const BROKEN_CACHE = 3;
    const CACHE_WRITE_ERROR = 4;
}
