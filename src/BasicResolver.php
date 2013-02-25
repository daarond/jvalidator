<?php
namespace JValidator;

require_once("IResolver.php");

class BasicResolver implements IResolver {

	static public function resolveExtend($extend, $dirname) {
		return $dirname . "/" . $extend;
	}
}