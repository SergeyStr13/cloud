<?php
namespace Pandora3\Widgets\Form\Sanitizers;

class SanitizerInteger {
	
	/**
	 * @param mixed $value
	 * @param array $arguments
	 * @return int
	 */
	public static function sanitize($value, array $arguments = []) {
		return (int) $value;
	}

}