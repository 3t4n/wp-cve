<?php

defined('ABSPATH') or die;

return [
	[
		'type' => 'Textarea',
		'name' => 'value',
		'label' => 'FPF_PHP_CODE',
		'description' => 'FPF_PHP_SELECTION_DESC',
		'rows' => 10,
		'filter' => 'php',
		'mode' => 'text/x-php'
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];