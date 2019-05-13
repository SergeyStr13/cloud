<?php
namespace Pandora3\Widgets\FieldPasswordView;

use Pandora3\Widgets\FieldText\FieldText;

class FieldPasswordView extends FieldText {

	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}

}