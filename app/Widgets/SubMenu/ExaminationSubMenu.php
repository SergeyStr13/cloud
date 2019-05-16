<?php
namespace App\Widgets\SubMenu;

class ExaminationSubMenu extends SubMenu {

	public const Examination = 'examination';
	public const Attestation = 'attestation';
	// public const Reports = 'reports';
	public const DebtStudents = 'debtStudents';

	protected $title = 'Успеваемость';

	protected function getItems(): array {
		return [
			self::Examination => 	['url' => '/examination',					'title' => 'Ведомости'],
			self::Attestation => 	['url' => '/examination/attestation',		'title' => 'Аттестация'],
			// self::Reports => 	['url' => '/examination/reports',			'title' => 'Отчеты'],
			self::DebtStudents => 	['url' => '/examination/debt-students',		'title' => 'Список должников'],
		];
	}

}
