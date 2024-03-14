<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'choices' => [
			'equal' => 'FPF_IS_EQUAL',
			'not_equal' => 'FPF_DOES_NOT_EQUAL',
			'less_than' => fpframework()->_('FPF_LESS_THAN'),
			'less_than_or_equal_to' => fpframework()->_('FPF_LESS_THAN_OR_EQUAL_TO'),
			'greater_than' => fpframework()->_('FPF_MORE_THAN'),
			'greater_than_or_equal_to' => fpframework()->_('FPF_MORE_THAN_OR_EQUAL_TO'),
		]
	],
	[
		'type' => 'Number',
		'name' => 'value',
		'label' => 'FPF_TIMEONSITE',
		'placeholder' => 360,
		'step' => 100,
		'addon' => strtolower(fpframework()->_('FPF_SECONDS'))
	]
];