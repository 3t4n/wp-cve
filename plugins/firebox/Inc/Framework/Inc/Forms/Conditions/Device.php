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
		'label' => 'FPF_DEVICE',
		'choices' => \FPFramework\Helpers\DevicesHelper::getDevices(),
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];