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
		'local_search' => true,
		'hide_ids' => true,
		'placeholder' => 'FPF_SELECT_A_COUNTRY',
		'label' => 'FPF_COUNTRY',
		'control_inner_class' => [],
		'items' => \FPFramework\Helpers\CountriesHelper::getCountriesList(),
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	],
	[
		'name' => 'geochecker',
		'type' => 'GeoLocationDBStatusChecker',
		'label' => '&nbsp;',
		'target' => '_blank',
		'plugin_name' => $plugin_name,
		'link' => true
	]
];