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
		'path' => '\FPFramework\Helpers\FireBoxFormHelper',
		'placeholder' => 'FPF_SELECT_A_FIREBOX_FORM_HINT',
		'label' => 'FPF_FIREBOX_FORM',
		'control_inner_class' => [],
		'items' => fpframework()->helper->fireboxform->getItems(),
		'lazyload' => true
	]
];