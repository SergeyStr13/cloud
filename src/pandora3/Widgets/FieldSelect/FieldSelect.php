<?php
namespace Pandora3\Widgets\FieldSelect;

use Pandora3\Widgets\FormField\FormField;

class FieldSelect extends FormField {

	/** @var array $options */
	protected $options;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $options
	 * @param array $context
	 */
	public function __construct(string $name, $value, array $options = [], array $context = []) {
		$this->options = $options;
		parent::__construct($name, $value, $context);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $context
	 * @return static
	 */
	public static function create(string $name, $value, array $context = []) {
		return new static($name, $value, $context['options'] ?? [], $context);
	}
	
	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}

	protected function getParams(): array {
		return array_replace( parent::getParams(), [
			'options' => $this->options
		]);
	}
	
	protected function beforeRender(array $context): array {
		if ($context['submitOnChange'] ?? false) {
			$attribs = $context['attribs'] ?? '';
			$context['attribs'] = $attribs.' onchange="this.form.submit()"';
		}
		if ($context['disabled'] ?? false) {
			$attribs = $context['attribs'] ?? '';
			$context['attribs'] = $attribs.' disabled';
		}
		return $context;
	}
	
}