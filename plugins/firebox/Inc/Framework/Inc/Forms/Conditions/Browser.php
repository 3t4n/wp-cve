<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'name' => 'value',
		'type' => 'Checkbox',
		'label' => 'FPF_BROWSER',
		'choices' => \FPFramework\Helpers\BrowsersHelper::getBrowsers()
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];