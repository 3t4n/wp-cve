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
		'path' => '\FPFramework\Helpers\TagsHelper',
		'placeholder' => 'FPF_POST_TAG_HINT',
		'label' => 'FPF_POST_TAG',
		'items' => fpframework()->helper->tags->getItems(),
		'lazyload' => true,
		'hide_flags' => false
	]
];