<?php
namespace Pandora3\Widgets\FieldTextarea;

use Pandora3\Widgets\FormField\FormField;

class FieldTextarea extends FormField {

	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}

	protected function beforeRender(array $context): array {
		$attribs = $context['attribs'] ?? '';
		if ($context['placeholder'] ?? '') {
			$attribs .= ' placeholder="'.$context['placeholder'].'"';
		}
		$height = $context['height'] ?? '';
		if ($height) {
			if (is_numeric($height)) {
				$height .= 'px';
			}
			$attribs .= ' style="height: '.$height.'"';
		}
		$context['attribs'] = $attribs;
		return $context;
	}

}