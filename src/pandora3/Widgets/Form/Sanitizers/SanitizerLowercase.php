<?php
namespace Pandora3\Widgets\Form\Sanitizers;

class SanitizerLowercase {

	/**
	 * @param string|null $value
	 * @param array $arguments
	 * @return string
	 */
	public static function sanitize(?string $value, array $arguments = []) {
		return mb_strtolower($value ?? '');
	}

}