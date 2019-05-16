<?php
/**
 * @var string $title
 * @var array $options
 */
$class = $class ?? '';
$attribs = $attribs ?? '';

?><span class="button-drop-down <?= $class ?>" data-click-outside="drop-down:removeClass.open" <?= $attribs ?>><?php
	?><a class="button button-small button-secondary" href="javascript:void(0)" onclick="this.nextSibling.toggleClass('open')"><?php
		?><?= $title ?><i class="mdi mdi-chevron-down"></i><?php
	?></a><?php
	?><div class="drop-down"><?php
		foreach ($options as $option):
			if (isset($option['title'])) {
				$title = $option['title'];
				$link = $option['link'] ?? 'javascript:void(0)';
				$optionClass = $option['class'] ?? '';
				$optionAttribs = $option['attribs'] ?? '';
			} else {
				[$title, $link] = $option;
				$optionAttribs = $option[2] ?? '';
				$optionClass = $option[3] ?? '';
			}
			?><a class="drop-down-item <?= $optionClass ?>" href="<?= $link ?>" <?= $optionAttribs ?>><?= $title ?></a><?php
		endforeach;
	?></div><?php
?></span><?php
