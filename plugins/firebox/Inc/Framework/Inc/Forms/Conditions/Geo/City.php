<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'name' => 'value',
		'type' => 'Repeater',
		'render_group' => true,
		'label' => 'FPF_CITY',
		'btn_label' => false,
		'class' => ['fpf-input-repeater'],
		'remove_action_class' => ['no-confirm'],
		'default_values' => [
			[
				'value' => ''
			]
		],
		'fields' => [
			[
				'name' => 'value',
				'type' => 'Text',
				'placeholder' => 'Athens, Berlin, Paris'
			]
		]
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