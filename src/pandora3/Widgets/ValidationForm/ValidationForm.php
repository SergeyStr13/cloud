<?php
namespace Pandora3\Widgets\ValidationForm;

use Pandora3\Core\Interfaces\RequestInterface;
use Pandora3\Widgets\Form\Form;
use Pandora3\Widgets\ValidationForm\Exceptions\UnregisteredRuleException;
use Pandora3\Widgets\ValidationForm\Rules\RuleRequired;

/**
 * @property-read array $values
 */
abstract class ValidationForm extends Form {

	/** @var string $loadMethod */
	protected $loadMethod = 'post';

	protected $autoLoad = false;

	/** @var array $ruleTypes */
	protected static $ruleTypes = [
		'required' => RuleRequired::class,
	];

	protected function messages(): array {
		return [];
	}

	abstract protected function rules(): array;

	protected function afterValidate(array $values): array {
		return $values;
	}

	protected function loadFromRequest(RequestInterface $request): array {
		return $request->all($this->loadMethod);
	}

	public function validate(): bool {
		if (!$this->request->isPost) {
			return false;
		}
		$isLoaded = $this->isLoaded;
		if (!$isLoaded) {
			$this->load();
		}
		$isValid = true;
		$rules = $this->rules();
		$messages = $this->messages();
		foreach (array_keys($this->fields) as $field) {
			$fieldRules = $rules[$field] ?? [];
			if (is_string($fieldRules)) {
				$fieldRules = [$fieldRules];
			}
			$value = $this->values[$field] ?? null;
			foreach ($fieldRules as $rule) {
				if (!array_key_exists($rule, self::$ruleTypes)) {
					throw new UnregisteredRuleException("Unregistered validation rule '$rule'");
				}
				$ruleClass = self::$ruleTypes[$rule];
				if (!$ruleClass::validate($value)) {
					$message = $messages[$field][$rule] ?? $ruleClass::$message;
					$label = $this->getField($field)->label;
					$this->fieldMessages[$field][] = str_replace('{:label}', $label, $message); // todo: message string params
					$isValid = false;
					break;
				}
			}
		}
		if ($isValid) {
			$this->values = $this->afterValidate($this->values);
		} else if (!$isLoaded) {
			// restore original non-sanitized values
			$this->values = $this->requestValues;
		}
		return $isValid;
	}

}