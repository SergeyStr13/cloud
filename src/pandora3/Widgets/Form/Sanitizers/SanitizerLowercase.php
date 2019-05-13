<?php
namespace Pandora3\Widgets\Form\Sanitizers;

class SanitizerLowercase {

	/**
	 * @param null|string $value
	 * @param array $arguments
	 * @return string
	 */
	public static function sanitize($value, array $arguments = []) {
		return mb_strtolower($value ?? '');
	}

}