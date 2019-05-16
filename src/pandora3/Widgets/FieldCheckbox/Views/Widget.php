<?php
/**
 * @var bool $isChecked
 * @var string $name
 */
$class = $class ?? '';
$label = $label ?? '';
$attribs = $attribs ?? '';
$disabled = $disabled ?? false;

?><label><?php
	?><div class="checkbox-wrap <?= $disabled ? 'disabled' : '' ?>"><?php
		?><input type="checkbox" class="checkbox <?= $class ?>" name="<?= $name ?>" value="1" <?= ($isChecked) ? 'checked' : '' ?> <?= $attribs ?>><?php
		?><i class="checkbox-icon"></i><?php
		if ($label):
			?><span class="checkbox-label"><?= htmlentities($label) ?></span><?php
		endif;
	?></div><?php
?></label><?php
