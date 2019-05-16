<?php
namespace App\Widgets\SubMenu;

class StipendSubMenu extends SubMenu {

	public const Assign = 'assign';
	public const StipendTypes = 'stipendTypes';
	public const Orders = 'orders';
	public const Protocols = 'protocols';

	protected $title = 'Стипендии';

	protected function getItems(): array {
		return [
			self::Assign => 		['url' => '/stipend',				'title' => 'Назначение'],
			self::StipendTypes =>	['url' => '/stipend/stipend-types',	'title' => 'Виды'],
			self::Orders =>			['url' => '/stipend/orders',		'title' => 'Приказы'],
			self::Protocols =>		['url' => '/stipend/protocols',		'title' => 'Протоколы'],
		];
	}

}