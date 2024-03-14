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
		'label' => 'FPF_CURRENT_PRODUCT_PRICE',
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
				'path' => '\FPFramework\Helpers\EDDHelper',
				'control_inner_class' => ['dropdown-min-width-200', 'truncate-text-200px'],
				'search_query_placeholder' => fpframework()->_('FPF_SEARCH_DOWNLOAD'),
				'placeholder' => 'FPF_PRODUCT',
				'render_group' => false,
				'items' => fpframework()->helper->edd->getItems(),
				'multiple' => false,
				'lazyload' => true
			],
			// Set Price
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px']
			],
			[
				'type' => 'Label',
				'render_group' => false,
				'text' => 'price'
			],
			[
				'name' => 'price_operator',
				'type' => 'Dropdown',
				'input_class' => ['width-auto'],
				'render_group' => false,
				'default' => 'equals',
				'choices' => [
					'equals' => strtolower(fpframework()->_('FPF_IS_EQUAL')),
					'less_than' => strtolower(fpframework()->_('FPF_FEWER_THAN')),
					'less_than_or_equal_to' => strtolower(fpframework()->_('FPF_FEWER_THAN_OR_EQUAL_TO')),
					'greater_than' => strtolower(fpframework()->_('FPF_GREATER_THAN')),
					'greater_than_or_equal_to' => strtolower(fpframework()->_('FPF_GREATER_THAN_OR_EQUAL_TO')),
					'range' => strtolower(fpframework()->_('FPF_IS_BETWEEN')),
				]
			],
			// Min Price
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields', 'inputs-width-80px'],
			],
			[
				'type' => 'Number',
				'name' => 'price_value1',
				'render_group' => false,
				'default' => 1,
				'min' => 1,
				'showon' => '[value][ITEM_ID][price_operator]:equals,fewer_than,fewer_than_equals,greater_than,greater_than_equals,range'
			],
			// Max Price
			[
				'type' => 'CustomDiv',
				'class' => ['fpf-side-by-side-items', 'fpf-gap-6px', 'fpf-range-input-fields', 'inputs-width-80px'],
				'showon' => '[value][ITEM_ID][price_operator]:range'
			],
			[
				'type' => 'Label',
				'name' => 'price_value_label',
				'render_group' => false,
				'text' => sprintf(' %s ', fpframework()->_('FPF_AND'))
			],
			[
				'type' => 'Number',
				'name' => 'price_value2',
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