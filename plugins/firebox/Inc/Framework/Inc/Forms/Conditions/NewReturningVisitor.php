<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'choices' => [
			'new' => fpframework()->_('FPF_VISITOR_IS_NEW'),
			'returning' => fpframework()->_('FPF_VISITOR_IS_RETURNING'),
		]
	],
	// Required so the condition can pass
	[
		'type' => 'Hidden',
		'name' => 'value',
		'value' => '1'
	],
];