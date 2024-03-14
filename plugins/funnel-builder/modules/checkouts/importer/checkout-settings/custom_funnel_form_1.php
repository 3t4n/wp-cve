<?php
$product_field  = WFACP_Common::get_product_field();
$advanced_field = WFACP_Common::get_advanced_fields();
$settings       = [
	'show_on_next_step' => [
		'single_step' => [
			'billing_email'       => 'false',
			'billing_first_name'  => 'false',
			'billing_last_name'   => 'false',
			'address'             => 'false',
			'shipping-address'    => 'false',
			'billing_phone'       => 'false',
			'shipping_calculator' => 'false',
		],
	],
];


$steps = [
	'single_step' => [
		'name'          => __( 'Step 1', 'funnel-builder' ),
		'slug'          => 'single_step',
		'friendly_name' => __( 'Single Step Checkout', 'funnel-builder' ),
		'active'        => 'yes',
	],
	'two_step'    => [
		'name'          => __( 'Step 2', 'funnel-builder' ),
		'slug'          => 'two_step',
		'friendly_name' => __( 'Two Step Checkout', 'funnel-builder' ),
		'active'        => 'no',
	],
	'third_step'  => [
		'name'          => __( 'Step 3', 'funnel-builder' ),
		'slug'          => 'third_step',
		'friendly_name' => __( 'Three Step Checkout', 'funnel-builder' ),
		'active'        => 'no',
	],
];

$pageLayout = [
	'steps'                       => WFACP_Common::get_default_steps_fields(),
	'fieldsets'                   => [
		'single_step' => [
			0 => [
				'name'        => 'Customer Information',
				'class'       => '',
				'sub_heading' => '',
				'fields'      => [
					[
						'label'        => __( 'First name', 'funnel-builder' ),
						'required'     => 'true',
						'class'        => [ 0 => 'form-row-first', ],
						'autocomplete' => 'given-name',
						'priority'     => '10',
						'type'         => 'text',
						'id'           => 'billing_first_name',
						'field_type'   => 'billing',
						'placeholder'  => '',
					],
					[
						'label'        => __( 'Last name', 'funnel-builder' ),
						'required'     => 'true',
						'class'        => [ 0 => 'form-row-last', ],
						'autocomplete' => 'family-name',
						'priority'     => '20',
						'type'         => 'text',
						'id'           => 'billing_last_name',
						'field_type'   => 'billing',
						'placeholder'  => '',
					],
					[
						'label'        => __( 'Email', 'funnel-builder' ),
						'required'     => 'true',
						'type'         => 'email',
						'class'        => [ 0 => 'form-row-wide', ],
						'validate'     => [ 0 => 'email', ],
						'autocomplete' => 'email username',
						'priority'     => '110',
						'id'           => 'billing_email',
						'field_type'   => 'billing',
						'placeholder'  => '',
					],

					[
						'label'        => __( 'Phone', 'funnel-builder' ),
						'type'         => 'tel',
						'class'        => [ 'form-row-wide' ],
						'id'           => 'billing_phone',
						'field_type'   => 'billing',
						'validate'     => [ 'phone' ],
						'placeholder'  => '',
						'autocomplete' => 'tel',
						'priority'     => 100,
					],
				],
			],
			[
				'name'        => __( 'Billing Details', 'funnel-builder' ),
				'class'       => '',
				'sub_heading' => '',
				'fields'      => [
					WFACP_Common::get_single_address_fields(),
					WFACP_Common::get_single_address_fields( 'shipping' ),

				],
			],
			[
				'name'        => __( 'Shipping Method', 'funnel-builder' ),
				'class'       => '',
				'sub_heading' => '',
				'html_fields' => [ 'shipping_calculator' => true ],
				'fields'      => [
					isset( $advanced_field['shipping_calculator'] ) ? $advanced_field['shipping_calculator'] : []
				],
			],

			[
				'name'        => __( 'Order Summary', 'funnel-builder' ),
				'class'       => 'wfacp_order_summary_box',
				'sub_heading' => '',
				'html_fields' => [
					'order_coupon'  => 'true',
					'order_summary' => 'true',
				],
				'fields'      => [
					$advanced_field['order_coupon'],
					$advanced_field['order_summary'],
				],
			],


		],
	],
	'product_settings'            => [],
	'have_coupon_field'           => 'true',
	'have_billing_address'        => 'true',
	'have_shipping_address'       => 'true',
	'have_billing_address_index'  => '5',
	'have_shipping_address_index' => '6',
	'enabled_product_switching'   => 'no',
	'have_shipping_method'        => 'true',
	'current_step'                => 'single_step',
];

$customizer_data = [
	'wfacp_form'          => [
		'wfacp_form_section_embed_forms_2_disable_steps_bar'                          => true,
		'wfacp_form_section_embed_forms_2_select_type'                                => "breadcrumb",
		'wfacp_form_section_embed_forms_2_step_form_max_width'                        => '664',
		'wfacp_form_section_embed_forms_2_form_border_type'                           => 'none',
		'wfacp_form_form_fields_1_embed_forms_2_billing_address_1'                    => 'wfacp-col-left-half',
		'wfacp_form_form_fields_1_embed_forms_2_billing_city'                         => 'wfacp-col-left-half',
		'wfacp_form_form_fields_1_embed_forms_2_billing_postcode'                     => 'wfacp-col-left-third',
		'wfacp_form_form_fields_1_embed_forms_2_billing_country'                      => 'wfacp-col-left-third',
		'wfacp_form_form_fields_1_embed_forms_2_billing_state'                        => 'wfacp-col-left-third',
		'wfacp_form_form_fields_1_embed_forms_2_shipping_address_1'                   => 'wfacp-col-left-half',
		'wfacp_form_form_fields_1_embed_forms_2_shipping_city'                        => 'wfacp-col-left-half',
		'wfacp_form_form_fields_1_embed_forms_2_shipping_postcode'                    => 'wfacp-col-left-third',
		'wfacp_form_form_fields_1_embed_forms_2_shipping_country'                     => 'wfacp-col-left-third',
		'wfacp_form_form_fields_1_embed_forms_2_shipping_state'                       => 'wfacp-col-left-third',
		'wfacp_form_form_fields_1_embed_forms_2_billing_first_name'                   => 'wfacp-col-left-half',
		'wfacp_form_form_fields_1_embed_forms_2_billing_last_name'                    => 'wfacp-col-left-half',
		'wfacp_form_section_embed_forms_2_sec_heading_color'                          => '#333',
		'wfacp_form_section_text_below_placeorder_btn'                                => __( "* 100% Secure &amp; Safe Payments *", 'woofunnels-aero-checkout' ),
		'wfacp_form_product_switcher_section_embed_forms_2_product_switcher_bg_color' => '#f7f7f7',
		'wfacp_form_section_embed_forms_2_heading_fs'                                 => 20,

		'wfacp_form_section_embed_forms_2_btn_order-place_bg_color'                     => '#24ae4e',
		'wfacp_form_section_embed_forms_2_btn_order-place_text_color'                   => '#ffffff',
		'wfacp_form_section_embed_forms_2_btn_order-place_bg_hover_color'               => '#7aa631',
		'wfacp_form_section_embed_forms_2_btn_order-place_text_hover_color'             => '#ffffff',
		'wfacp_form_section_embed_forms_2_btn_order-place_border_radius'                => '4',


	],
	'wfacp_order_summary' => [
		'wfacp_order_summary_section_embed_forms_2_order_summary_hide_img' => false,
	]
];

return [
	'default_customizer_value' => $customizer_data,
	'page_layout'              => $pageLayout,
	'page_settings'            => $settings
];
