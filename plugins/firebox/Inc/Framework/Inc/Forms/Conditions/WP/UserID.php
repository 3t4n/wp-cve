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
		'control_inner_class' => [],
		'placeholder' => fpframework()->_('FPF_TYPE_A_USER'),
		'label' => 'FPF_USER',
		'path' => '\FPFramework\Helpers\UserIDHelper',
		'items' => fpframework()->helper->userid->getItems(),
		'lazyload' => true
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];