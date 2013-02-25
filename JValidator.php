<?php
	/**
	 * JValidator main include file
	 * Required configuration:
	 * - JVALIDATOR_SCHEMA_DIR
	 * - JVALIDATOR_CACHE_DIR
	 * - JVALIDATOR_USE_CACHE
	 *
	 * Optional configuration:
	 * - JVALIDATOR_EXTERNAL_RESOLVER
	 */
	define("JVALIDATOR_ROOT_DIR", dirname(__FILE__));
	define("JVALIDATOR_SRC_DIR", JVALIDATOR_ROOT_DIR . "/src");

	require_once JVALIDATOR_SRC_DIR . '/Exceptions.php';
	require_once JVALIDATOR_SRC_DIR . '/SchemaProvider.php';
	require_once JVALIDATOR_SRC_DIR . '/Builder.php';
	require_once JVALIDATOR_SRC_DIR . '/validator.php';
