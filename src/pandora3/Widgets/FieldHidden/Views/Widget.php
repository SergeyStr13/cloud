<?php
/**
 * @var mixed $textValue
 * @var string $name
 */
$class = $class ?? '';
$attribs = $attribs ?? '';

?><input type="hidden" class="<?= $class ?>" name="<?= $name ?>" value="<?= htmlentities($textValue) ?>" <?= $attribs ?>><?php
