<?php
namespace App\Widgets\SubMenu;

class StudyPlanSubMenu extends SubMenu {

	public const StudyPlans = 'studyPlans';
	public const Disciplines = 'disciplines';

	protected $title = 'Учебные планы';

	protected function getItems(): array {
		return [
			self::Directions => 	['url' => '/directions',				'title' => 'Направления'],
			self::Specialities =>	['url' => '/directions/specialities',	'title' => 'Профили'],
			//self::StudyPlans =>		['url' => '/directions/study-plans',	'title' => 'Учебные планы'],
		];
	}

}
