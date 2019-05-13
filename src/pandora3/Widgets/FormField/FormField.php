<?php
namespace Pandora3\Widgets\FormField;

use Pandora3\Core\Widget\Exception\WidgetRenderException;
use Pandora3\Core\Widget\Widget;

/**
 * @property-read mixed $value
 * @property-read string $label
 * @property-read array $context
 */
abstract class FormField extends Widget {

	/** @var string $name */
	protected $name;

	/** @var mixed $value */
	protected $value;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $context
	 */
	public function __construct(string $name, $value, array $context = []) {
		parent::__construct($context);
		$this->name = $name;
		$this->setValue($value);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $context
	 * @return static
	 */
	public static function create(string $name, $value, array $context = []) {
		return new static($name, $value, $context);
	}

	/**
	 * @param string $property
	 * @return mixed
	 */
	public function __get(string $property) {
		$methods = [
			'value' => 'getValue',
			'label' => 'getLabel',
			'context' => 'getContext',
		];
		$methodName = $methods[$property] ?? '';
		if ($methodName && method_exists($this, $methodName)) {
			return $this->{$methodName}();
		} else {
			return null;
			// throw new \Exception('Method or property does not exists'); todo:
		}
	}
	
	/**
	 * Values passed to context before rendering
	 * @return array
	 */
	protected function getParams(): array {
		return [
			'name' => $this->name,
			'value' => $this->getValue(),
		];
	}

	/**
	 * @return array
	 */
	protected function getContext(): array {
		return $this->context;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value ?? $this->context['default'] ?? null;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void {
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return $this->context['label'] ?? '';
	}
	
	// todo: move to trait
	/**
	 * Preparing context to render
	 * @param array $context    merged from: local context, params and context overrides
	 * @return array
	 */
	protected function beforeRender(array $context): array {
		return $context;
	}

	// todo: PhpRenderer
	/**
	 * @param array $contextOverride
	 * @return string
	 * @throws WidgetRenderException
	 */
	public function render(array $contextOverride = []): string {
		// $renderer = new PhpRenderer(__PATH__.'/Views');
		$viewPath = $this->getView();
		$context = $this->beforeRender(array_replace(
			$this->context,
			$this->getParams(),
			$contextOverride
		));
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