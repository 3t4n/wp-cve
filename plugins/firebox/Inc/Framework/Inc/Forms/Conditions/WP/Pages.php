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
		'path' => '\FPFramework\Helpers\PagesHelper',
		'placeholder' => 'FPF_PAGE_HINT',
		'label' => 'FPF_PAGE',
		'items' => fpframework()->helper->pages->getItems(),
		'lazyload' => true,
		'hide_flags' => false
	]
];