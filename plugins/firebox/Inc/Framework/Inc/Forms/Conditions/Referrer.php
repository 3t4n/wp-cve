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
		'label' => 'FPF_REFERRER_URL',
		'description' => 'FPF_REFERRER_URL_DESC',
		'render_group' => true,
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
				'placeholder' => 'www.facebook.com'
			]
		]
	]
];