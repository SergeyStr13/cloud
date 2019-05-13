<?php
namespace Pandora3\Plugins\Twig;

use Twig\Extension\AbstractExtension as TwigExtension;
use Twig_Filter as TwigFilter;

class JsFilterExtension extends TwigExtension {

	public function getFilters() {
		return [
			new TwigFilter('js', function($value) {
				/* if (is_array($value) && empty($value)) {
					return '{}';
				} */
				// todo: escaping inside string literals
				return str_replace('"', '\'', json_encode($value, JSON_UNESCAPED_UNICODE));
			})
		];
	}

}