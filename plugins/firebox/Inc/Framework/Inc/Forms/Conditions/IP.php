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
		'label' => 'FPF_IP_ADDRESS',
		'class' => ['fpf-input-repeater'],
		'remove_action_class' => ['no-confirm'],
		'render_group' => true,
		'btn_label' => false,
		'default_values' => [
			[
				'value' => ''
			]
		],
		'fields' => [
			[
				'name' => 'value',
				'type' => 'Text',
				'placeholder' => '180.150.1.6'
			]
		]
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];