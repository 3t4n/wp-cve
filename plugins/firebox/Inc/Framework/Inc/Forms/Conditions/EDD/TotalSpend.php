<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'choices' => [
			'equal' => fpframework()->_('FPF_IS_EQUAL'),
			'not_equal' => fpframework()->_('FPF_DOES_NOT_EQUAL'),
			'less_than' => fpframework()->_('FPF_LESS_THAN'),
			'less_than_or_equal_to' => fpframework()->_('FPF_LESS_THAN_OR_EQUAL_TO'),
			'greater_than' => fpframework()->_('FPF_MORE_THAN'),
			'greater_than_or_equal_to' => fpframework()->_('FPF_MORE_THAN_OR_EQUAL_TO')
		]
	],
	[
		'type' => 'Number',
		'name' => 'value',
		'label' => fpframework()->_('FPF_TOTAL_SPEND'),
		'placeholder' => 30,
		'addon' => function_exists('edd_get_currency') ? edd_get_currency() : ''
	]
];