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
		'path' => '\FPFramework\Helpers\WooCommerceHelper',
		'placeholder' => 'FPF_SELECT_WOOCOMMERCE_PRODUCTS',
		'label' => 'FPF_PRODUCT',
		'control_inner_class' => [],
		'items' => fpframework()->helper->woocommerce->getItems(),
		'lazyload' => true
	]
];