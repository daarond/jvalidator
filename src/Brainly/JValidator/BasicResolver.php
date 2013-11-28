<?php
namespace Brainly\JValidator;

use Brainly\JValidator\IResolver;

class BasicResolver implements IResolver {

	static public function resolveExtend($extend, $dirname) {
		return $dirname . "/" . $extend;
	}
}
