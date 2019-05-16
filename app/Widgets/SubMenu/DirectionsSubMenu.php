<?php
namespace App\Widgets\SubMenu;

class DirectionsSubMenu extends SubMenu {

	public const Directions = 'directions';
	public const Specialities = 'specialities';
	//public const StudyPlans = 'studyPlans';

	protected $title = 'Направления';

	protected function getItems(): array {
		return [
			self::Directions => 	['url' => '/directions',				'title' => 'Направления'],
			self::Specialities =>	['url' => '/directions/specialities',	'title' => 'Профили'],
			//self::StudyPlans =>		['url' => '/directions/study-plans',	'title' => 'Учебные планы'],
		];
	}

}
