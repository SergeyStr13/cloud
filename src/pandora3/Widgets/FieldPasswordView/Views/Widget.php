<?php
/**
 * @var \Pandora3\Libs\Widget\Widget $widget
 * @var mixed $value
 * @var string $name
 */
$visible = $visible ?? false;
$class = $class ?? '';
$attribs = $attribs ?? '';

$widget->addScript('scripts/widget.js');

?><input type="<?= $visible ? 'text' : 'password' ?>" class="password-view <?= $class ?>" name="<?= $name ?>" <?php
	?>value="<?= htmlentities($value) ?>" <?= $attribs ?>><?php
?><a class="field-button" href="javascript:void(0)" tabindex="-1" title="Показать пароль" onclick="togglePassword(this)"><i class="mdi"></i></a><?php
