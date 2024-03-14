<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'type' => 'SearchDropdown',
		'name' => 'value',
		'path' => '\FPFramework\Helpers\MenuHelper',
		'placeholder' => 'FPF_MENU_ITEM_HINT',
		'label' => 'FPF_MENU_ITEM',
		'items' => fpframework()->helper->menu->getItems(),
		'lazyload' => true,
		'hide_flags' => false
	],
	[
		'name' => 'params.inc_children',
		'type' => 'Toggle',
		'label' => 'FPF_MENU_INCLUDE_CHILD',
		'description' => 'FPF_MENU_INCLUDE_CHILD_DESC',
		'default' => 0,
		'choices' => [
			0 => 'FPF_NO',
			1 => 'FPF_YES',
			2 => 'FPF_ONLY'
		]
	]
];