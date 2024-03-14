<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'choices' => [
			'equal' => fpframework()->_('FPF_IS_BETWEEN'),
			'not_equal' => fpframework()->_('FPF_IS_NOT_BETWEEN')
		]
	],
	[
		'name' => 'params.publish_up',
		'type' => 'Datepicker',
		'apply_timezone' => true,
		'class' => ['fpf-flex-row-fields'],
		'placeholder' => '0000-00-00 00:00',
		'label' => fpframework()->_('FPF_DATETIME_START'),
		'extra_atts' => [
			'data-timepicker' => true
		],
	],
	[
		'name' => 'params.publish_down',
		'type' => 'Datepicker',
		'apply_timezone' => true,
		'class' => ['fpf-flex-row-fields'],
		'placeholder' => '0000-00-00 00:00',
		'label' => fpframework()->_('FPF_DATETIME_END'),
		'extra_atts' => [
			'data-timepicker' => true
		],
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];