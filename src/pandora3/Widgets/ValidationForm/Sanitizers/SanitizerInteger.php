<?php
namespace Pandora3\Widgets\ValidationForm\Sanitizers;

class SanitizerInteger {
	public static function sanitize($value) {
		return (int) $value;
	}
}