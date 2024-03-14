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
		'btn_label' => false,
		'label' => 'FPF_URL',
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
				'placeholder' => '/blog/welcome-to-our-site'
			]
		]
	],
	[
		'name' => 'params.regex',
		'type' => 'FPToggle',
		'label' => 'FPF_REGUAL_EXPRESSION',
		'description' => 'FPF_REGUAL_EXPRESSION_DESC',
		'default' => '0'
	]
];