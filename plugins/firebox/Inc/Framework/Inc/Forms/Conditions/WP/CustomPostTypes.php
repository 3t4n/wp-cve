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
		'path' => '\FPFramework\Helpers\CptsHelper',
		'placeholder' => 'FPF_CPTS_HINT',
		'label' => 'FPF_CPT',
		'items' => fpframework()->helper->cpts->getItems(),
		'lazyload' => true,
		'hide_flags' => false
	]
];