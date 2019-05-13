<?php
/**
 * @var mixed $value
 * @var string $name
 */
$class = $class ?? '';
$attribs = $attribs ?? '';

?><textarea class="<?= $class ?>" name="<?= $name ?>" <?= $attribs ?>><?php
?><?= htmlentities($value) ?><?php
?></textarea><?php
