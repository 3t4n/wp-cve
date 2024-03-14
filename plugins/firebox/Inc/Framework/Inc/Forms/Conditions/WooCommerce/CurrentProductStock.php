<?php

defined('ABSPATH') or die;

return [
	[
		'name' => 'operator',
		'type' => 'Comparator'
	],
	[
		'name' => 'value',
		'type' => 'Repeater',
		'btn_label' => false,
		'render_group' => true,
		'label' => 'FPF_CURRENT_PRODUCT_STOCK',
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
				'items' => fpframework()->helper->woocommerce->getItems(),
				'multiple' => false,
				'lazyload' => true
			],
			// Set Stock
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px']
			],
			[
				'type' => 'Label',
				'render_group' => false,
				'text' => fpframework()->_('FPF_STOCK_LC')
			],
			[
				'name' => 'stock_operator',
				'type' => 'Dropdown',
				'input_class' => ['width-auto'],
				'render_group' => false,
				'default' => 'equals',
				'choices' => [
					'equals' => strtolower(fpframework()->_('FPF_IS_EQUAL')),
					'less_than' => strtolower(fpframework()->_('FPF_LESS_THAN')),
					'less_than_or_equal_to' => strtolower(fpframework()->_('FPF_LESS_THAN_OR_EQUAL_TO')),
					'greater_than' => strtolower(fpframework()->_('FPF_MORE_THAN')),
					'greater_than_or_equal_to' => strtolower(fpframework()->_('FPF_MORE_THAN_OR_EQUAL_TO')),
					'range' => strtolower(fpframework()->_('FPF_IS_BETWEEN')),
				]
			],
			// Min Stock
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields', 'inputs-width-80px'],
			],
			[
				'type' => 'Number',
				'name' => 'stock_value1',
				'render_group' => false,
				'default' => 1,
				'min' => 1,
				'showon' => '[value][ITEM_ID][stock_operator]:equals,fewer_than,fewer_than_equals,greater_than,greater_than_equals,range'
			],
			// Max Stock
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields', 'inputs-width-80px'],
				'showon' => '[value][ITEM_ID][stock_operator]:range'
			],
			[
				'type' => 'Label',
				'name' => 'stock_value_label',
				'render_group' => false,
				'text' => sprintf(' %s ', fpframework()->_('FPF_AND'))
			],
			[
				'type' => 'Number',
				'name' => 'stock_value2',
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