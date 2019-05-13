<?php
namespace App\Models\Enum;

/**
 * Trait Enum
 */
trait EnumOptions {

	public static function getOptions(): array {
		return array_filter(self::$titles, function ($key) {
			return $key !== 0;
		}, ARRAY_FILTER_USE_KEY);
	}

	public static function getOptionsOnly(array $only = []): array {
		return array_filter(self::$titles, function ($key) use ($only) {
			return in_array($key, $only);
		}, ARRAY_FILTER_USE_KEY);
	}

	public static function getOptionsExclude(array $exclude = []): array {
		return array_filter(self::$titles, function ($key) use ($exclude) {
			return $key !== self::None && !in_array($key, $exclude);
		}, ARRAY_FILTER_USE_KEY);
	}

}