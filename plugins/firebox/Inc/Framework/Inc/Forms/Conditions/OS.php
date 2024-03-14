<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'type' => 'Checkbox',
		'name' => 'value',
		'label' => 'FPF_OS',
		'choices' => \FPFramework\Helpers\OsesHelper::getOSes()
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];