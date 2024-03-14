<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'type' => 'SearchDropdown',
		'name' => 'value',
		'path' => '\FPFramework\Helpers\UserRoleHelper',
		'placeholder' => 'FPF_USER_GROUP_HINT',
		'label' => 'FPF_USER_GROUP',
		'items' => fpframework()->helper->userrole->getUserRoles(),
		'hide_ids' => true
	],
	[
		'name' => 'note',
		'type' => 'ConditionRuleValueHint'
	]
];