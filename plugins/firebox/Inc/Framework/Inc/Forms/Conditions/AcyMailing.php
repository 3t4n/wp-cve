<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'name' => 'value',
		'type' => 'SearchDropdown',
		'path' => '\FPFramework\Helpers\AcyMailingHelper',
		'placeholder' => 'FPF_CONDITION_ACYMAILING_HINT',
		'label' => 'FPF_CONDITION_ACYMAILING',
		'description' => 'FPF_CONDITION_ACYMAILING_DESC',
		'items' => fpframework()->helper->acymailing->getItems(),
		'lazyload' => true
	]
];