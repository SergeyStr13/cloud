<?php
namespace App\Widgets\Menu;

use Pandora3\Libs\Widget\Widget;

class Menu extends Widget {
	
	protected function getView(): string {
		return 'Menu/Menu.twig';
	}
	
}