<?php
namespace Pandora3\Widgets\ValidationForm\Sanitizers;

class SanitizerBoolean {
	public static function sanitize($value) {
		return (bool) $value;
	}
}