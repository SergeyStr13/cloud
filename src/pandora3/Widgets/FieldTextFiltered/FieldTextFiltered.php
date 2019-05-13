<?php
namespace Pandora3\Widgets\FieldTextFiltered;

use Pandora3\Widgets\FieldText\FieldText;

class FieldTextFiltered extends FieldText {

	protected function getParams(): array {
		$type = $this->context['type'];
		if ($type === 'number') {
			$this->context['type'] = 'text';
			$allowedChars = '0-9\-\.';
		} else if ($type === 'int') {
			$this->context['type'] = 'text';
			$allowedChars = '0-9\-';
		} else {
			$allowedChars = $this->context['allowedChars'] ?? null;
		}

		return array_replace( parent::getParams(), [
			'allowedChars' => $allowedChars
		]);
	}

	protected function beforeRender(array $context): array {
		if ($context['allowedChars'] ?? null) {
			$attribs = $context['attribs'] ?? '';
			$context['attribs'] = $attribs.' onkeypress="filterKey(event, \''.$context['allowedChars'].'\')"';
		}
		return $context;
	}
	
}