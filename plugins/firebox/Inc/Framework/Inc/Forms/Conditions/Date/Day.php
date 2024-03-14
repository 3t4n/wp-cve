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
		'placeholder' => fpframework()->_('FPF_SELECT_A_DAY'),
		'label' => fpframework()->_('FPF_DAY_OF_WEEK'),
		'class' => ['fpf-flex-row-fields'],
		'items' => [
			'Mon' => fpframework()->_('FPF_MONDAY'),
			'Tue' => fpframework()->_('FPF_TUESDAY'),
			'Wed' => fpframework()->_('FPF_WEDNESDAY'),
			'Thu' => fpframework()->_('FPF_THURSDAY'),
			'Fri' => fpframework()->_('FPF_FRIDAY'),
			'Sat' => fpframework()->_('FPF_SATURDAY'),
			'Sun' => fpframework()->_('FPF_SUNDAY'),
			'Weekend' => fpframework()->_('FPF_WEEKEND'),
			'Weekday' => fpframework()->_('FPF_WEEKDAYS')
		]
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];