<?php
namespace App\Plugins\Storage\Forms;


use Illuminate\Support\Collection as SupportCollection;
use Pandora3\Widgets\ValidationForm\ValidationForm;

class DirectoryForm extends ValidationForm {

	/** @var array $values */
	public $values;

	protected function getFields(): array {
		return [
			'title' =>              ['type' => 'input',         'label' => 'Название'],
		];
	}

	protected function rules(): array {
		// TODO: Implement rules() method.
	}

}