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
		'path' => '\FPFramework\Helpers\PostsHelper',
		'placeholder' => 'FPF_POST_HINT',
		'label' => 'FPF_POST',
		'items' => fpframework()->helper->posts->getItems(),
		'lazyload' => true,
		'hide_flags' => false
	]
];