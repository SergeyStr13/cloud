<?php
/**
 * @var mixed $value
 * @var string $name
 * @var array $options
 */
$class = $class ?? '';
$label = $label ?? '';
$attribs = $attribs ?? '';
$disabled = $disabled ?? false;

?><div class="radio-group <?= $class ?> <?= $disabled ? 'disabled' : '' ?>" <?= $attribs ?>><?php
	if ($label):
		?><div class="label"><?= htmlentities($label) ?></div><?php
	endif;
	/* ?><select name="<?= $name ?>" <?= ($class) ? 'class="'.$class.'"' : '' ?> <?= $attribs ?>><?php */
	foreach ($options as $optionValue => $optionParams):
		if (is_scalar($optionParams)) {
			$optionParams = ['title' => $optionParams];
		}
		$optionParams = (object) $optionParams;
		$optionAttribs = $optionParams->attribs ?? '';
		$optionDisabled = ($optionParams->disabled ?? false) || $disabled;
		if ($optionDisabled) {
			$optionAttribs .= ' disabled';
		}
		$isChecked = ($value === $optionValue) || ($value === ''.$optionValue);
		?><label><?php
			?><div class="radio-wrap <?= $optionDisabled ? 'disabled' : '' ?>"><?php
				?><input class="radio" type="radio" name="<?= $name ?>" value="<?= $optionValue ?>" <?= ($isChecked) ? 'checked' : '' ?> <?= $optionAttribs ?>><?php
				?><i class="radio-icon"></i><?php
				?><span class="radio-label"><?= htmlentities($optionParams->title) ?></span><?php
			?></div><?php
		?></label><?php
	endforeach;

?></div><?php
