<?php
namespace Pandora3\Widgets\FieldCheckboxGroup;

use Pandora3\Widgets\FormField\FormField;

class FieldCheckboxGroup extends FormField {

	/** @var array $options */
	
	protected $options;
	
	/** @var bool $flags */
	
	protected $flags;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $options
	 * @param array $context
	 *
	 */
	public function __construct(string $name, $value, array $options = [], array $context = []) {
		$this->options = $options;
		$this->flags = $context['flags'] ?? false;
		parent::__construct($name, $value, $context);
	}
	
	/**
	 * @param mixed $value
	 */
	public function setValue($value): void {
		if (is_bool($value)) {
			$value = (int) $value;
		}
		if ($this->flags) {
			$flags = (int) $value;
			$value = [];
			for ($i = 1; $i <= $flags; $i *= 2) {
				if ($i & $flags) {
					$value[] = $i;
				}
			}
		} else {
			if (!is_array($value)) {
				$value = $value ? [$value] : [];
				
			}
		}
		parent::setValue($value);
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
		if ($context['disabled'] ?? false) {
			$attribs = $context['attribs'] ?? '';
			$context['attribs'] = $attribs.' disabled';
		}
		return $context;
	}
	
}