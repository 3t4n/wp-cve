<?php

defined('ABSPATH') or die;

return [
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-field-control-group', 'flex-dir-row']
	],
	[
		'type' => 'Label',
		'class' => ['fpf-width-190'],
		'text' => 'FPF_LAST_PURCHASED_DATE'
	],
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-side-by-side-items', 'fpf-gap-6px'],
	],
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'input_class' => ['width-auto'],
		'render_group' => false,
		'default' => 'within',
		'choices' => [
			'within' => 'FPF_IS_IN_THE_LAST_X_DAYS',
			'equal' => 'FPF_IS',
			'before' => 'FPF_IS_BEFORE',
			'after' => 'FPF_IS_AFTER',
			'range' => 'FPF_IS_BETWEEN',
		]
	],
	// Within
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-side-by-side-items', 'fpf-gap-6px'],
		'showon' => '[operator]:within'
	],
	[
		'type' => 'Number',
		'name' => 'params.within_value',
		'render_group' => false,
		'default' => 1,
		'step' => 1,
		'min' => 1,
	],
	[
		'type' => 'Dropdown',
		'name' => 'params.within_period',
		'render_group' => false,
		'choices' => [
			'hours' => 'FPF_HOURS',
			'days' => 'FPF_DAYS',
			'weeks' => 'FPF_WEEKS',
			'months' => 'FPF_MONTHS'
		],
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	// Date 1
	[
		'type' => 'CustomDiv',
		'showon' => '[operator]:before,after,range,equal',
	],
	[
		'name' => 'value',
		'type' => 'Datepicker',
		'apply_timezone' => true,
		'class' => ['fpf-flex-row-fields'],
		'placeholder' => '0000-00-00 00:00',
		'render_group' => false
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	// Range
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-side-by-side-items', 'fpf-gap-6px'],
		'showon' => '[operator]:range'
	],
	[
		'type' => 'Label',
		'render_group' => false,
		'text' => 'and',
	],
	[
		'name' => 'params.value2',
		'type' => 'Datepicker',
		'apply_timezone' => true,
		'class' => ['fpf-flex-row-fields'],
		'placeholder' => '0000-00-00 00:00',
		'render_group' => false
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	]
];