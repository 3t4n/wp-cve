<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'choices' => [
			'equal' => 'FPF_IS_BETWEEN',
			'not_equal' => 'FPF_IS_NOT_BETWEEN'
		]
	],
	[
		'name' => 'params.publish_up',
		'type' => 'Timepicker',
		'placeholder' => '00:00',
		'label' => fpframework()->_('FPF_TIMEPICKER_START'),
		'extra_atts' => [
			'data-nocalendar' => true,
			'data-timepicker' => true
		]
	],
	[
		'name' => 'params.publish_down',
		'type' => 'Timepicker',
		'placeholder' => '00:00',
		'label' => fpframework()->_('FPF_TIMEPICKER_END'),
		'extra_atts' => [
			'data-nocalendar' => true,
			'data-timepicker' => true
		]
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];