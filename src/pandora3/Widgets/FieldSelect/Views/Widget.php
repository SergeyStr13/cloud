<?php
/**
 * @var mixed $value
 * @var string $name
 * @var array $options
 */
$class = $class ?? '';
$attribs = $attribs ?? '';
$disabled = $disabled ?? false;
$groupOptions = $groupOptions ?? false;

$renderOptions = function($options) use ($value) {
	foreach ($options as $optionValue => $params):
		if (is_scalar($params)) {
			$params = ['title' => $params];
		}
		$params = (object) $params;
		$attribs = $params->attribs ?? '';
		$selected = ($value === $optionValue) || ($value === ''.$optionValue);
		?><option value="<?= $optionValue ?>" <?= ($selected) ? 'selected' : '' ?> <?= $attribs ?>><?= htmlentities($params->title) ?></option><?php
	endforeach;
}

?><div class="select-wrap <?= $disabled ? 'disabled' : '' ?>"><?php
	// aria-labelledby=""
	?><select name="<?= $name ?>" <?= ($class) ? 'class="'.$class.'"' : '' ?> <?= $attribs ?>><?php
		if ($groupOptions) {
			foreach ($options as $groupTitle => $subOptions):
				?><optgroup label="<?= htmlentities($groupTitle) ?>"><?php
					echo $renderOptions($subOptions);
				?></optgroup><?php
			endforeach;
		} else {
			echo $renderOptions($options);
		}
	?></select><?php
	?><div class="custom-select"></div><?php
?></div><?php
