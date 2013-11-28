<?php
namespace Brainly\JValidator;

use Brainly\JValidator\BasicResolver;
use Brainly\JValidator\Exceptions\InvalidSchemaException;
use Brainly\JValidator\Exceptions\SchemaBuilderException;
use Brainly\JValidator\Exceptions\SchemaProviderException;

class SchemaProvider {
	static private $useCache  = false;
	static private $schemaDir = '/schemas';
	static private $cacheDir  = '/cache';
	static private $_resolver = '\Brainly\JValidator\BasicResolver';

	static public function setCustomResolver($resolver) {
		self::$_resolver = $resolver;
	}

	static public function setUseCache($useCache) {
		self::$useCache = $useCache;
	}

	static public function setSchemaDir($schemaDir) {
		self::$schemaDir = $schemaDir;
	}

	static public function setCacheDir($cacheDir) {
		self::$cacheDir = $cacheDir;
	}

	static public function resolveExtend($extend, $dirname) {
		$resolver = self::$_resolver;
		return $resolver::resolveExtend($extend, $dirname);
	}

	/**
	 * Returns completely builded JSON Schema
	 * @param string $fName Schema file name
	 * @param bool $forceNoCache Don't use cache
	 * @return string JSON encoded schema
	 * @throws SchemaProviderException
	 * @throws SchemaBuilderException
	 */
	static public function getSchema($fName, $forceNoCache = false) {
		if(self::$useCache && !$forceNoCache) {
			$cached = self::getFromCache($fName);

			if($cached !== false) {
				return $cached;
			}
		}

		// Not from cache, so build schema
		$rawSchema = self::getRawSchema($fName);
		$dirname = dirname($fName);

		try {
			$builder = new Builder();
			$builded = $builder->buildSchema($rawSchema, $dirname);
		} catch(BuilderException $e) {
			switch($e->getCode()) {
				case BuilderException::NO_EXTEND_FILE:
				case BuilderException::BROKEN_EXTEND:
				case BuilderException::INVALID_TYPE:
				case BuilderException::INVALID_PROPERTY:
					throw new InvalidSchemaException($e->getMessage());
					break;

				default:
					throw new Exception($e->getMessage());
			}
		}

		if(self::$useCache && !$forceNoCache) {
			self::putToCache($fName, $builded);
		}

		return $builded;
	}

	/**
	 * Returns raw JSON Schema
	 * @param string $fName Schema file name
	 * @return string JSON encoded schema
	 * @throws SchemaProviderException when schema file doesn't exists or can't be parsed
	 */
	static public function getRawSchema($fName) {
		$fName = self::$schemaDir . '/' . $fName;

		if(!file_exists($fName)) {
			$msg = sprintf("Schema file '%s' not found", $fName);
			$code = SchemaProviderException::SCHEMA_NOT_FOUND;
			throw new SchemaProviderException($msg, $code);
		}

		$schema = file_get_contents($fName);
		$decoded = json_decode($schema);

		if(is_null($decoded)) {
			$msg = sprintf("Unable to decode file '%s' as JSON", $fName);
			$code = SchemaProviderException::UNPARSABLE_JSON;
			throw new SchemaProviderException($msg, $code);
		}

		return $schema;
	}

	/**
	 * Reads schema from cache
	 * @param $fName Schema file name used to compute cache key
	 * @return false | string JSON encoded schema or false if there is no schema in cache
	 * @throws SchemaProviderException when can not decode cached JSON
	 */
	static private function getFromCache($fName) {
		$cacheFile = self::$cacheDir . '/' . md5($fName);

		if(!file_exists($cacheFile)) {
			return false;
		}

		$schema = file_get_contents($cacheFile);
		$decoded = json_decode($schema);

		if(is_null($decoded)) {
			$msg = sprintf("Broken cache for schema '%s', cache file '%s'",
						   $fName, $cacheFile);
			$code = SchemaProviderException::BROKEN_CACHE;
			throw new SchemaProviderException($msg, $code);
		}

		return $schema;
	}

	/**
	 * Puts schema into cache
	 * @param string $fName Schema file name used to compute destination cache key
	 * @param string $schema JSON encoded schema
	 * @throws SchemaProviderException when unable to write to cache
	 */
	static private function putToCache($fName, $schema) {
		$cacheFile = self::$cacheDir . '/' . md5($fName);

		$result = file_put_contents($cacheFile, $schema);

		if(is_null($result)) {
			$msg = sprintf("Unable to write schema '%s' to cache '%s'",
						   $fName, $cacheFile);
			$code = SchemaProviderException::BROKEN_CACHE;
			throw new SchemaProviderException($msg, $code);
		}
	}
}
