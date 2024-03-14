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
		'path' => '\FPFramework\Helpers\EDDHelper',
		'placeholder' => 'FPF_SELECT_EDD_PRODUCTS',
		'label' => 'FPF_PRODUCT',
		'control_inner_class' => [],
		'items' => fpframework()->helper->edd->getItems(),
		'lazyload' => true
	]
];