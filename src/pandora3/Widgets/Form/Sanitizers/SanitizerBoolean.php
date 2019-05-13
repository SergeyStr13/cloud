<?php
namespace Pandora3\Widgets\Form\Sanitizers;

class SanitizerBoolean {
	
	/**
	 * @param mixed $value
	 * @param array $arguments
	 * @return bool
	 */
	public static function sanitize($value, array $arguments = []) {
		return (bool) $value;
	}

}