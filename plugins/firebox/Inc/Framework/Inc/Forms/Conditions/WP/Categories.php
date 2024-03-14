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
		'path' => '\FPFramework\Helpers\CategoriesHelper',
		'placeholder' => 'FPF_POST_CATEGORY_HINT',
		'label' => 'FPF_POST_CATEGORY',
		'items' => fpframework()->helper->categories->getItems(),
		'lazyload' => true,
		'hide_flags' => false
	]
];