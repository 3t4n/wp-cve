<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'value',
		'type' => 'Text',
		'label' => fpframework()->_('FPF_COOKIE_NAME'),
		'placeholder' => fpframework()->_('FPF_COOKIE_NAME'),
	],
	[
		'name' => 'params.operator',
		'type' => 'Comparator',
		'choices' => [
			'exists' => fpframework()->_('FPF_EXISTS'),
			'not_exists' => fpframework()->_('FPF_NOT_EXISTS'),
			'empty' => fpframework()->_('FPF_IS_EMPTY'),
			'not_empty' => fpframework()->_('FPF_IS_NOT_EMPTY'),
			'equal' => fpframework()->_('FPF_IS_EQUAL'),
			'not_equal' => fpframework()->_('FPF_DOES_NOT_EQUAL'),
			'includes' => fpframework()->_('FPF_CONTAINS'),
			'not_includes' => fpframework()->_('FPF_DOES_NOT_CONTAIN'),
			'starts_with' => fpframework()->_('FPF_STARTS_WITH'),
			'not_starts_with' => fpframework()->_('FPF_DOES_NOT_START_WITH'),
			'ends_with' => fpframework()->_('FPF_ENDS_WITH'),
			'not_ends_with' => fpframework()->_('FPF_DOES_NOT_END_WITH')
		]
	],
	[
		'name' => 'params.content',
		'type' => 'Text',
		'placeholder' => fpframework()->_('FPF_COOKIE_CONTENT'),
		'label' => fpframework()->_('FPF_COOKIE_CONTENT'),
		'showon' => '[params][operator]:equal,not_equal,includes,not_includes,starts_with,not_starts_with,ends_with,not_ends_with'
	]
];