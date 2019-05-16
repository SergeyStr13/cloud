<?php
namespace App\Widgets\DropDownButton;

use Pandora3\Libs\Widget\Exception\WidgetRenderException;
use Pandora3\Libs\Widget\Widget;

class DropDownButton extends Widget {

	/** @var array $options */
	protected $options;

	/**
	 * @param array $options
	 * @param array $context
	 */
	public function __construct(array $options = [], array $context = []) {
		parent::__construct($context);
		$this->options = $options;
	}

	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}
	
	/**
	 * @param array $contextOverride
	 * @return string
	 * @throws WidgetRenderException
	 */
	public function render(array $contextOverride = []): string {
		$viewPath = $this->getView();
		$context = array_replace($this->context, ['options' => $this->options], $contextOverride);
		try {
			extract($context);
			ob_start();
			include($viewPath.'.php'); // $renderer->render($viewPath, $context);
			return ob_get_clean();
		} catch (\Throwable $ex) {
			$className = get_class($this);
			throw new WidgetRenderException("Rendering view '$viewPath' failed for [$className]", E_WARNING, $ex);
		}
	}
	
}