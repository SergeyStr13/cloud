<?php
/**
 * @var string $inputType
 * @var mixed $value
 * @var string $name
 */
$class = $class ?? '';
$attribs = $attribs ?? '';

?><input type="<?= $inputType ?>" class="<?= $class ?>" name="<?= $name ?>" <?php
	?><?= $inputType !== 'password' ? 'value="'.htmlentities($value).'"' : '' ?> <?= $attribs ?>><?php
