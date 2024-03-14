<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'name' => 'value',
		'type' => 'SearchDropdown',
		'local_search' => true,
		'hide_ids' => true,
		'label' => fpframework()->_('FPF_MONTH'),
		'placeholder' => fpframework()->_('FPF_SELECT_A_MONTH'),
		'class' => ['fpf-flex-row-fields'],
		'items' => [
			'Jan' => fpframework()->_('FPF_JANUARY'),
			'Feb' => fpframework()->_('FPF_FEBRUARY'),
			'Mar' => fpframework()->_('FPF_MARCH'),
			'Apr' => fpframework()->_('FPF_APRIL'),
			'May' => fpframework()->_('FPF_MAY'),
			'Jun' => fpframework()->_('FPF_JUNE'),
			'Jul' => fpframework()->_('FPF_JULY'),
			'Aug' => fpframework()->_('FPF_AUGUST'),
			'Sep' => fpframework()->_('FPF_SEPTEMBER'),
			'Oct' => fpframework()->_('FPF_OCTOBER'),
			'Nov' => fpframework()->_('FPF_NOVEMBER'),
			'Dec' => fpframework()->_('FPF_DECEMBER')
		]
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];