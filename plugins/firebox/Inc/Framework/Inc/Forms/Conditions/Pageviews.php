<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'choices' => [
			'equal' => fpframework()->_('FPF_IS_EQUAL'),
			'not_equal' => fpframework()->_('FPF_DOES_NOT_EQUAL'),
			'less_than' => fpframework()->_('FPF_FEWER_THAN'),
			'less_than_or_equal_to' => fpframework()->_('FPF_FEWER_THAN_OR_EQUAL_TO'),
			'greater_than' => fpframework()->_('FPF_GREATER_THAN'),
			'greater_than_or_equal_to' => fpframework()->_('FPF_GREATER_THAN_OR_EQUAL_TO')
		]
	],
	[
		'type' => 'Number',
		'name' => 'value',
		'label' => fpframework()->_('FPF_PAGEVIEWS'),
		'placeholder' => 3,
		'addon' => strtolower(fpframework()->_('FPF_PAGE_VIEWS'))
	]
];