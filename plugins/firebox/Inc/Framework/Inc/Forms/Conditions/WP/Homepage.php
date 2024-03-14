<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'params.operator',
		'type' => 'Comparator',
		'label' => fpframework()->_('FPF_VISITOR_IS'),
		'choices' => [
			'is' => fpframework()->_('FPF_ON_HOMEPAGE'),
			'is_not' => fpframework()->_('FPF_NOT_ON_HOMEPAGE'),
		]
	]
];