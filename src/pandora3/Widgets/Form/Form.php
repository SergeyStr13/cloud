<?php
namespace Pandora3\Widgets\Form;

use Pandora3\Core\Application\Application;
use Pandora3\Core\Interfaces\RequestInterface;
use Pandora3\Widgets\FieldCheckbox\FieldCheckbox;
use Pandora3\Widgets\FieldCheckboxGroup\FieldCheckboxGroup;
use Pandora3\Widgets\FieldHidden\FieldHidden;
use Pandora3\Widgets\FieldPasswordView\FieldPasswordView;
use Pandora3\Widgets\FieldRadio\FieldRadio;
use Pandora3\Widgets\FieldSelect\FieldSelect;
use Pandora3\Widgets\FieldText\FieldText;
use Pandora3\Widgets\FieldTextarea\FieldTextarea;
use Pandora3\Widgets\FieldTextFiltered\FieldTextFiltered;
use Pandora3\Widgets\FormField\FormField;
use Pandora3\Core\Widget\Exception\WidgetRenderException;
use Pandora3\Widgets\ValidationForm\Exceptions\UnregisteredSanitizerException;
use Pandora3\Widgets\Form\Sanitizers\{SanitizerDate,SanitizerInteger,SanitizerBoolean,SanitizerLowercase};

/**
 * @property-read string $action
 * @property-read string $message
 * @property-read bool $isUpdate
 * @property-read array $values
 * @property-read object|null $model
 */
abstract class Form {

	/** @var RequestInterface $request */
	protected $request;

	/** @var array $values */
	protected $values = [];

	/** @var array $requestValues */
	protected $requestValues = [];

	/** @var array $requestExcludeFields */
	protected $requestExcludeFields = [];

	/** @var array $fieldMessages */
	protected $fieldMessages = [];

	/** @var string $message */
	protected $message = '';

	/** @var string $action */
	protected $action;

	/** @var object|null $model */
	protected $model;

	/** @var string $method */
	protected $method = 'post';

	/** @var bool $autoLoad */
	protected $autoLoad = true;
	
	/** @var bool $isLoaded */
	protected $isLoaded = false;

	/** @var FormField[] $fields */
	protected $fields = [];

	/** @var array $buttons */
	protected $buttons = [];

	/** @var string $id */
	protected $id = '';

	/** @var string $baseUri */
	protected $baseUri = null;

	/** @var bool $files */
	protected $files = false;

	/** @var array */
	protected static $currentParams = [];

	/** @var int $index */
	protected static $index = 1;

	/** @var array $fieldTypes */
	protected static $fieldTypes = [
		'input' => FieldText::class,
		'hidden' => FieldHidden::class,
		'password' => FieldText::class,
		'passwordView' => FieldPasswordView::class,
		'select' => FieldSelect::class,
		'checkbox' => FieldCheckbox::class,
		'checkboxGroup' => FieldCheckboxGroup::class,
		'radio' => FieldRadio::class,
		'textarea' => FieldTextarea::class,
		'inputFiltered' => FieldTextFiltered::class,
		'number' => FieldTextFiltered::class,
		'int' => FieldTextFiltered::class,
	];

	/**
	 * @param RequestInterface $request
	 * @param object|null $model
	 * @param string|null $action
	 */
	public function __construct(RequestInterface $request, $model = null, $action = null) {
		$this->request = $request;
		$this->action = $action ?? $request->uri;
		if (is_array($model)) {
			$model = (object) $model;
		}
		$this->model = $model ? (object) $this->setModel($model) : null;
		$this->initFields($this->getFields());
		$this->initButtons($this->getButtons());
		$app = Application::getInstance();
		if ($this->baseUri === null) {
			$this->baseUri = preg_replace('#/$#', '', $app->baseUri);
		}
		if ($this->autoLoad) {
			$this->load();
		}
	}

	/**
	 * @param object $model
 	 * @return object
 	 */
	protected function setModel($model) {
		return $model;
	}

	abstract protected function getFields(): array;

	protected static $sanitizerTypes = [
		'bool' => SanitizerBoolean::class,
		'int' => SanitizerInteger::class,
		'date' => SanitizerDate::class,
		'lower' => SanitizerLowercase::class,
	];

	protected function sanitizers(): array {
		return [];
	}

	protected function afterLoad(array $values): array {
		return $values;
	}

	// abstract protected function getButtons(): array;

	// todo: move to App\Widgets\ApplicationForm
	protected function getButtons(): array {
		return [
			'save' => [
				'type' => 'submit',             'title' => $this->isUpdate ? 'Сохранить' : 'Добавить',
				'class' => 'button-primary',    'icon' => '<i class="mdi mdi-check"></i>',
			],
			'cancel' => [
				'type' => 'link',               'title' => 'Отмена',
				'icon' => '<i class="mdi mdi-close"></i>',
			]
		];
	}

	/**
	 * @param array $fieldsData
	 */
	protected function initFields(array $fieldsData): void {
		foreach ($fieldsData as $name => $params) {
			$field = null;
			$value = $this->model->$name ?? $params['default'] ?? null;
			// todo: checks
			$fieldType = $params['type'] ?? '';
			$ignoreRequest = $params['ignoreRequest'] ?? false;
			$className = self::$fieldTypes[$fieldType] ?? '';
			// если поля нет warning
			$field = $className::create($name, $value, $params);
			$this->fields[$name] = $field;
			if ($this->model && property_exists($this->model, $name)) {
				$this->model->$name = $field->value;
			}
			if ($ignoreRequest) {
				$this->requestExcludeFields[$name] = true;
			}
			// dump($name, property_exists($model, $name));
		}
	}

	/**
	 * @param array $buttonsData
	 */
	protected function initButtons(array $buttonsData): void {
		foreach ($buttonsData as $name => $params) {
			$this->buttons[$name] = (object) $params;
			// type, title, class, icon, href
		}
	}

	/**
	 * @param string $property
	 * @return mixed
	 */
	public function __get(string $property) {
		$methods = [
			'action' => 'getAction',
			'isUpdate' => 'isUpdate',
			'values' => 'getValues',
			'model' => 'getModel',
			'message' => 'getMessage',
		];
		$methodName = $methods[$property] ?? '';
		if ($methodName && method_exists($this, $methodName)) {
			return $this->{$methodName}();
		} else {
			return null;
			// throw new \Exception('Method or property does not exists'); todo:
		}
	}

	public function getField(string $name): FormField {
		return $this->fields[$name] ?? null;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function get(string $name) {
		if (isset($this->values[$name])) {
			return $this->values[$name];
		}
		return $this->model->$name ?? null;
	}

	/**
	 * @param array ...$arguments
 	 * @return array
 	 */
	public function only(...$arguments): array {
		$values = [];
		foreach ($arguments as $name) {
			$values[$name] = $this->get($name);
		}
		return $values;
	}

	/**
	 * @param string $name
	 * @param mixed $value
 	 */
	public function set(string $name, $value): void {
		$this->values[$name] = $value;
	}

	public function getValues(): array {
		return $this->values;
	}

	/* *
	 * @return array
	 */
	/* public function getValues(): array {
		return $this->model ? (array) $this->model : [];
	} */

	protected function loadFromRequest(RequestInterface $request): array {
		return $request->all($this->method);
	}

	public function load(): array {
		$values = $this->requestValues = array_diff_key(
			$this->loadFromRequest($this->request),
			$this->requestExcludeFields
		);
		$values = $this->sanitize($values);
		$this->isLoaded = true;
		// $field->context['useRequest']
		return $this->values = $this->afterLoad($values);
	}

	protected function sanitize(array $values): array {
		$sanitizers = $this->sanitizers();
		foreach ($sanitizers as $field => $fieldSanitizers) {
			if (!array_key_exists($field, $this->fields)) {
				continue;
			}
			if (is_string($fieldSanitizers)) {
				$fieldSanitizers = [$fieldSanitizers];
			}
			$value = $values[$field] ?? null;
			if ($value !== null) {
				foreach ($fieldSanitizers as $key => $sanitizer) {
					$arguments = [];
					if (!is_numeric($key)) {
						$arguments = $sanitizer;
						$sanitizer = $key;
					}
					if (!array_key_exists($sanitizer, self::$sanitizerTypes)) {
						throw new UnregisteredSanitizerException("Unregistered sanitizer '$sanitizer'");
					}
					$sanitizerClass = self::$sanitizerTypes[$sanitizer];
					$value = $sanitizerClass::sanitize($value, $arguments);
				}
				$values[$field] = $value;
			}
		}
		return $values;
	}

	/**
	 * @return mixed
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @return string
	 */
	public function getAction(): string {
		return $this->action;
	}

	/**
	 * @return bool
	 */
	public function isUpdate(): bool {
		return $this->model !== null;
	}

	/**
	 * @return string
 	 */
	protected static function generateId(): string {
		return 'form-'.(self::$index++);
	}

	// todo: CSRF token field
	/**
	 * @param array $params
	 * @return string
	 */
	public function begin($params = []): string {
		$id = $params['id'] ?? $this->id;
		$files = $params['files'] ?? $this->files;
		$action = $params['action'] ?? $this->action;
		$method = $params['method'] ?? $this->method;
		$baseUri = $params['baseUri'] ?? $this->baseUri;

		if (!$id) {
			$id = self::generateId();
		}
		$class = $params['class'] ?? '';
		$attribs = $params['attribs'] ?? '';

		self::$currentParams = compact('id', 'files', 'action', 'method', 'baseUri', 'class');

		if ($files) {
			$attribs .= ' enctype="multipart/form-data"';
		}
		if ($id != '') {
			$id = 'id="'.$id.'"';
		}
		if ($class != '') {
			$class = 'class="'.$class.'"';
		}
		ob_start();
			?><form <?= $id ?> <?= $class ?> action="<?= $baseUri.$action ?>" method="<?= $method ?>" <?= $attribs ?>><?php
		return ob_get_clean();
	}

	public function end(): string {
		self::$currentParams = [];
		return '</form>';
	}

	/**
	 * @param string $name
	 * @param array $params
	 * @return string
	 * @throws WidgetRenderException
	 */
	public function field(string $name, array $params = []): string {
		$field = $this->fields[$name] ?? null;
		if (!$field) {
			// todo: "warning field \"$name\" not found"
			return '';
		}
		$field->setValue($this->get($name));
		return self::renderField($field, $params);
	}

	public function fakePassword(): string {
		return '<div style="overflow: hidden; height: 0;"><input type="password" name="fakePassword"></div>';
	}

	/**
	 * @param FormField $field
	 * @param array $params
	 * @return string
	 * @throws WidgetRenderException
	 */
	protected static function renderField(FormField $field, array $params = []): string {
		$html = $field->render($params);
		$wrap = !($field instanceof FieldHidden);
		$wrap = $params['wrap'] ?? $field->context['wrap'] ?? $wrap;
		if (!$wrap) {
			return $html;
		}
		$label = $params['label'] ?? $field->label;
		$labelAfter = $params['labelAfter'] ?? false;
		// todo: move to template
		ob_start();
		?><div class="field <?= $labelAfter ? 'label-after' : '' ?>"><?php
			if (
				$label &&
				!($field instanceof FieldCheckbox) &&
				!($field instanceof FieldRadio) &&
				!($field instanceof FieldCheckboxGroup)
			) { // todo: remove instanceof
				if ($labelAfter) {
					?><label><?php
						echo $html;
						?><div class="label"><?= htmlentities($label) ?></div><?php // id="field1-label"
					?></label><?php
				} else {
					?><label><?php
						?><div class="label"><?= htmlentities($label) ?></div><?php // id="field1-label"
						echo $html;
					?></label><?php
				}
			} else {
				echo $html;
			}
		?></div><?php
		return ob_get_clean();
	}

	/**
	 * @param string $name
	 * @param array $params
	 * @return string
	 */
	public function button(string $name, array $params = []): string {
		$button = $this->buttons[$name] ?? null;
		if (!$button) {
			// todo: "warning button '$name' not found"
			return '';
		}
		$params['name'] = $name;
		if (!empty($params['class'])) {
			$params['class'] = ltrim($button->class.' '.$params['class']);
		}
		return self::renderButton(array_replace((array) $button, $params));
	}

	/**
	 * @param array $params
	 * @return string
	 */
	protected function renderButton(array $params): string {
		$type = $params['type'] ?? 'link';
		$class = $params['class'] ?? '';
		$attribs = $params['attribs'] ?? '';
		$title = $params['title'] ?? '';
		$icon = $params['icon'] ?? '';
		$href = $params['href'] ?? 'javascript:void(0)';
		$width = $params['width'] ?? '';

		if ($type === 'submit') {
			$href = 'javascript:document.forms[\''.self::$currentParams['id'].'\'].submit()';
		}
		if ($width) {
			if (is_numeric($width)) {
				$width .= 'px';
			}
			$attribs .= ' style="width: '.$width.'"';
		}
		ob_start();
		/* ?><button class="button <?= $class ?>" onclick="<?= $href ?>"><?= $icon.$title ?></button><?php */
		?><a class="button <?= $class ?>" href="<?= $href ?>" <?= $attribs ?>><?= $icon . htmlentities($title) ?></a><?php
		return ob_get_clean();
	}

	/**
	 * @param array $params
	 * @return string
	 * @throws WidgetRenderException
	 */
	public function render(array $params = []): string {
		ob_start();
		echo $this->begin($params);
			foreach ($this->fields as $name => $field) {
				// echo $field->render();
				echo self::renderField($field);
			}
			echo '<div class="form-toolbar">';
				foreach ($this->buttons as $button) {
					echo self::renderButton((array) $button);
				}
			echo '</div>';
		echo $this->end();
		return ob_get_clean();
	}

	public function setFieldMessage(string $field, string $message) {
		$this->fieldMessages[$field] = $message;
	}

	public function getFieldMessage(string $field): string {
		return $this->fieldMessages[$field] ?? '';
	}

	public function setMessage(string $message) {
		$this->message = $message;
	}

	public function getMessage(): string {
		return $this->message;
	}

	public function getMessages(): string {
		$messages = $this->fieldMessages;
		if ($this->message) {
			$messages = array_replace(['' => $this->message], $messages);
		}
		if (!$messages) {
			return '';
		}
		ob_start();
		?><div class="form-messages"><?php
			foreach ($messages as $field => $fieldMessages) {
				if (!is_array($fieldMessages)) {
					$fieldMessages = [$fieldMessages];
				}
				foreach ($fieldMessages as $message) {
					?><div class="message message-danger"><?php
						/* ?><i class="mdi mdi-alert-circle"></i><?php */
						?><span><?= $message ?></span><?php
					?></div><?php
				}
			}
		?></div><?php
		return ob_get_clean();
	}

}