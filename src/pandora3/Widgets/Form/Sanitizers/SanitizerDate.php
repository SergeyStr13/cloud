<?php
namespace Pandora3\Widgets\Form\Sanitizers;

use Pandora3\Libs\Time\Date;

class SanitizerDate {
	
	/**
	 * @param string|null $value
	 * @param array $arguments
	 * @return Date|null
	 */
	public static function sanitize(?string $value, array $arguments = []): ?Date {
		$format = $arguments['format'] ?? 'd.m.Y';
		return $value ? Date::createFromFormat($format, $value) : null;
	}

}