<?php
namespace Pandora3\Widgets\FieldText;

use Pandora3\Widgets\FormField\FormField;

class FieldText extends FormField {

	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}

	protected function getParams(): array {
		return array_replace( parent::getParams(), [
			'inputType' => ($this->context['type'] === 'input') ? 'text' : $this->context['type']
		]);
	}

	protected function beforeRender(array $context): array {
		$context = parent::beforeRender($context);
		if ($context['placeholder'] ?? '') {
			$attribs = $context['attribs'] ?? '';
			$context['attribs'] = $attribs.' placeholder="'.$context['placeholder'].'"';
		}
		if ($context['disabled'] ?? false) {
			$attribs = $context['attribs'] ?? '';
			$context['attribs'] = $attribs.' disabled';
		}
		return $context;
	}

}