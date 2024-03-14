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
		'path' => '\FPFramework\Helpers\LanguageHelper',
		'placeholder' => 'FPF_LANGUAGE_HINT',
		'label' => 'FPF_LANGUAGE',
		'items' => fpframework()->helper->language->getItems(),
		'hide_flags' => false
	]
];