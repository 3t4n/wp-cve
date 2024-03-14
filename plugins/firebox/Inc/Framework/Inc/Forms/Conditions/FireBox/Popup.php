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
		'path' => '\FPFramework\Helpers\FireBoxHelper',
		'placeholder' => 'FPF_SELECT_A_FIREBOX_HINT',
		'label' => 'FPF_FIREBOX',
		'control_inner_class' => [],
		'items' => fpframework()->helper->firebox->getItems(),
		'lazyload' => true
	]
];