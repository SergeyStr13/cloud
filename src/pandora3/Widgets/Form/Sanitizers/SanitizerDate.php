<?php
namespace Pandora3\Widgets\Form\Sanitizers;

use Pandora3\Libs\Time\Date;

class SanitizerDate {
	
	/**
	 * @param null|mixed $value
	 * @param array $arguments
	 * @return Date|null
	 */
	public static function sanitize($value, array $arguments = []) {
		$format = $arguments['format'] ?? 'd.m.Y';
		return $value ? Date::createFromFormat($format, $value) : null;
	}

}