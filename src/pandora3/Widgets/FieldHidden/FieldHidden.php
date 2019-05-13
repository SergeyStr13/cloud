<?php
namespace Pandora3\Widgets\FieldHidden;

use Pandora3\Widgets\FormField\FormField;

class FieldHidden extends FormField {

	/** @var bool $isArray */
	protected $isArray;

	/** @var string $delimiter */
	protected $delimiter;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $context
	 */
	public function __construct(string $name, $value, array $context = []) {
		$this->isArray = $context['array'] ?? false;
		$this->delimiter = $context['delimiter'] ?? ',';
		parent::__construct($name, $value, $context);
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void {
		if ($this->isArray && is_string($value)) {
			$value = explode($this->delimiter, $value);
		}
		parent::setValue($value);
	}

	public function getTextValue() {
		$value = $this->getValue();
		if ($this->isArray && is_array($value)) {
			return implode($this->delimiter, $value);
		}
		return $value;
	}

	protected function getParams(): array {
		return array_replace( parent::getParams(), [
			'textValue' => $this->getTextValue()
		]);
	}

	protected function getView(): string {
		return __DIR__.'/Views/Widget';
	}

}