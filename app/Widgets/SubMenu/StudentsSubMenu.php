<?php
namespace App\Widgets\SubMenu;

class StudentsSubMenu extends SubMenu {

	public const Students = 'students';
	public const PersonalData = 'personalData';
	public const Orders = 'orders';

	protected $title = 'Студенты';

	protected function getItems(): array {
		return [
			self::Students => 		['url' => '/students',					'title' => 'Студенты'],
			self::PersonalData => 	['url' => '/students/personal-data',	'title' => 'Персональные данные'],
			self::Orders => 		['url' => '/students/orders',			'title' => 'Приказы'],
		];
	}

}
