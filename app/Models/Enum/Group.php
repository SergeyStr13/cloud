<?php
namespace App\Models\Enum;

class Group {

	use EnumOptions;

	public const None		= 0;

	public const Admin		= 1;
	public const Registered	= 2;

	protected static $titles = [
		self::None => null,
		self::Admin => 'Администратор',
		self::Registered => 'Пользователь',
	];

	public static function getTitle(int $group): string {
		return self::$titles[$group] ?? 'UNKNOWN_GROUP';
	}

}