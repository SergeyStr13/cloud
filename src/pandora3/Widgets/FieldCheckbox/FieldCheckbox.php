<?php
namespace Pandora3\Widgets\FieldCheckbox;

use Pandora3\Widgets\FormField\FormField;

class FieldCheckbox extends FormField {

	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}

	protected function getParams(): array {
		return array_replace( parent::getParams(), [
			'isChecked' => $this->value ? true : false
		]);
	}

}