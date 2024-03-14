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
		'placeholder' => 'FPF_SELECT_A_CONTINENT',
		'label' => 'FPF_CONTINENT',
		'control_inner_class' => [],
		'items' => \FPFramework\Helpers\ContinentsHelper::getContinents()
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