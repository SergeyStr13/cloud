<?php
namespace App\Widgets\SubMenu;

use Pandora3\Libs\Widget\Widget;

abstract class SubMenu extends Widget {

	/** @var string $title */
	protected $title = '';

	abstract protected function getItems(): array;

	/**
	 * @param string|null $active
	 * @param array $context
	 */
	public function __construct(?string $active = null, array $context = []) {
		$items = collect($this->getItems());
		$context = array_replace([
			'title' => $this->title,
			'items' => $items,
			'active' => $active ?? $items->keys()->first(),
		], $context);
		parent::__construct($context);
	}

	protected function getView(): string {
		return 'Menu/SubMenu.twig';
	}

}