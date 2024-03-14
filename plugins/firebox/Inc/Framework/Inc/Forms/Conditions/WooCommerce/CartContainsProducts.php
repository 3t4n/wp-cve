<?php

defined('ABSPATH') or die;

$products = fpframework()->helper->woocommerce->getItems();

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'name' => 'value',
		'type' => 'Repeater',
		'render_group' => true,
		'label' => 'FPF_PRODUCTS_IN_CART',
		'btn_label' => false,
		'class' => ['fpf-repeater-gap-1', 'only-remove-action'],
		'remove_action_class' => ['no-confirm'],
		'default_values' => [
			[
				'value' => ''
			]
		],
		'fields' => [
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px']
			],
			[
				'type' => 'SearchDropdown',
				'name' => 'value',
				'path' => '\FPFramework\Helpers\WooCommerceHelper',
				'control_inner_class' => ['dropdown-min-width-200', 'truncate-text-200px'],
				'search_query_placeholder' => fpframework()->_('FPF_SEARCH_DOWNLOAD'),
				'placeholder' => 'FPF_PRODUCT',
				'render_group' => false,
				'items' => $products,
				'multiple' => false,
				'lazyload' => true
			],
			// "Set Quantity" toggle
			[
				'type' => 'CustomDiv',
				'showon' => '[value][ITEM_ID][set_quantity]!:1'
			],
			[
				'type' => 'Checkbox',
				'name' => 'set_quantity',
				'input_class' => ['fpf-hidden-choice-input', 'fpf-choice-label-link'],
				'render_group' => false,
				'choices' => [
					'1' => fpframework()->_('FPF_SET_QUANTITY_LC')
				]
			],
			[
				'type' => 'CustomDiv',
				'position' => 'end'
			],
			// Set Quantity
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px'],
				'showon' => '[value][ITEM_ID][set_quantity]:1'
			],
			[
				'type' => 'Label',
				'render_group' => false,
				'text' => 'quantity'
			],
			[
				'name' => 'quantity_operator',
				'type' => 'Dropdown',
				'input_class' => ['width-auto'],
				'render_group' => false,
				'default' => 'any',
				'choices' => [
					'any' => 'is any',
					'equals' => strtolower(fpframework()->_('FPF_IS_EQUAL')),
					'less_than' => strtolower(fpframework()->_('FPF_FEWER_THAN')),
					'less_than_equals' => strtolower(fpframework()->_('FPF_FEWER_THAN_OR_EQUAL_TO')),
					'greater_than' => strtolower(fpframework()->_('FPF_GREATER_THAN')),
					'greater_than_equals' => strtolower(fpframework()->_('FPF_GREATER_THAN_OR_EQUAL_TO')),
					'range' => strtolower(fpframework()->_('FPF_IS_BETWEEN')),
				]
			],
			// Min Quantity
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields'],
				'showon' => '[value][ITEM_ID][quantity_operator]!:any'
			],
			[
				'type' => 'Number',
				'name' => 'quantity_value1',
				'render_group' => false,
				'default' => 1,
				'min' => 1,
				'showon' => '[value][ITEM_ID][quantity_operator]:equals,less_than,less_than_equals,greater_than,greater_than_equals,range'
			],
			// Max Quantity
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields'],
				'showon' => '[value][ITEM_ID][quantity_operator]:range'
			],
			[
				'type' => 'Label',
				'name' => 'quantity_value_label',
				'render_group' => false,
				'text' => ' and '
			],
			[
				'type' => 'Number',
				'name' => 'quantity_value2',
				'render_group' => false,
				'default' => 1,
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
				'type' => 'CustomDiv',
				'position' => 'end'
			],
		]
	]
];