<?php
/**
 * @var mixed $value
 * @var string $name
 */
$visible = $visible ?? false;
$class = $class ?? '';
$attribs = $attribs ?? '';

?><input type="<?= $visible ? 'text' : 'password' ?>" class="password-view <?= $class ?>" name="<?= $name ?>" <?php
	?>value="<?= htmlentities($value) ?>" <?= $attribs ?>><?php
?><a class="field-button" href="javascript:void(0)" tabindex="-1" onclick="this.previousSibling.type = (this.previousSibling.type !== 'password' ? 'password' : 'text')"><i class="mdi"></i></a><?php
