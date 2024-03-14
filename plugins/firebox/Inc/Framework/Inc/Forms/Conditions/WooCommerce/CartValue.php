<?php

defined('ABSPATH') or die;

return [
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-field-control-group', 'flex-dir-row']
	],
	[
		'type' => 'Label',
		'text' => 'FPF_CART_AMOUNT',
		'class' => ['fpf-width-190']
	],
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-side-by-side-items', 'fpf-gap-6px']
	],
	[
		'name' => 'params.total',
		'type' => 'Dropdown',
		'input_class' => ['width-auto'],
		'render_group' => false,
		'default' => 'total',
		'choices' => [
			'total' => fpframework()->_('FPF_TOTAL_LC'),
			'subtotal' => fpframework()->_('FPF_SUBTOTAL_LC')
		]
	],
	[
		'name' => 'operator',
		'type' => 'Comparator',
		'input_class' => ['width-auto'],
		'render_group' => false,
		'choices' => [
			'equal' => strtolower(fpframework()->_('FPF_IS_EQUAL')),
			'not_equal' => strtolower(fpframework()->_('FPF_DOES_NOT_EQUAL')),
			'less_than' => strtolower(fpframework()->_('FPF_LESS_THAN')),
			'less_than_or_equal_to' => strtolower(fpframework()->_('FPF_LESS_THAN_OR_EQUAL_TO')),
			'greater_than' => strtolower(fpframework()->_('FPF_MORE_THAN')),
			'greater_than_or_equal_to' => strtolower(fpframework()->_('FPF_MORE_THAN_OR_EQUAL_TO')),
			'range' => strtolower(fpframework()->_('FPF_IS_BETWEEN'))
		]
	],
	// Number 1
	[
		'type' => 'Number',
		'name' => 'value',
		'placeholder' => 2,
		'input_class' => ['width-auto'],
		'render_group' => false,
		'render_top' => false,
		'min' => 1
	],
	// Number 2
	[
		'type' => 'CustomDiv',
		'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields'],
		'showon' => '[operator]:range'
	],
	[
		'type' => 'Label',
		'name' => 'value2_label',
		'render_group' => false,
		'text' => sprintf(' %s ', fpframework()->_('FPF_AND'))
	],
	[
		'type' => 'Number',
		'name' => 'params.value2',
		'input_class' => ['width-auto'],
		'render_group' => false,
		'render_top' => false,
		'min' => 1
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	[
		'type' => 'CustomDiv',
		'position' => 'end'
	],
	[
		'type' => 'FPToggle',
		'name' => 'params.exclude_shipping_cost',
		'label' => 'FPF_EXCLUDE_SHIPPING_COST'
	],
];