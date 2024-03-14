<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_additional_info';
$config['title']    = 'Additional Information';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                        => $config['slug'],
	'position'                  => 16,
	'xlwcty_accordion_head_end' => 'yes',
	'xlwcty_accordion_title'    => $config['title'],
	'xlwcty_icon'               => 'xlwcty-fa xlwcty-fa-sticky-note-o',
	'fields'                    => array(
		array(
			'name'                       => __( 'Enable', 'woo-thank-you-page-nextmove-lite' ),
			'id'                         => $config['slug'] . '_enable',
			'type'                       => 'xlwcty_switch',
			'description'                => 'WooCommerce or other plugins sometimes add additional information to native Thank You pages. This component will show those additional information blocks.<br>For example WooCommerce displays additional information for payment gateways such as BACS or Cheque.',
			'row_classes'                => array( 'xlwcty_is_enable' ),
			'label'                      => array(
				'on'  => __( 'Yes', 'woo-thank-you-page-nextmove-lite' ),
				'off' => __( 'No', 'woo-thank-you-page-nextmove-lite' ),
			),
			'before_row'                 => array( 'XLWCTY_Admin_CMB2_Support', 'cmb_before_row_cb' ),
			'xlwcty_accordion_title'     => $config['title'],
			'xlwcty_component'           => $config['slug'],
			'xlwcty_is_accordion_opened' => false,
			'after'                      => include_once __DIR__ . '/help.php',
		),


		array(
			'name'        => __( 'Border', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_border_style',
			'type'        => 'select',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_select_small', 'xlwcty_combine_3_field_start' ),
			'before'      => '<p class="xlwcty_mt5 xlwcty_mb5">Style</p>',
			'options'     => array(
				'dotted' => __( 'Dotted', 'woo-thank-you-page-nextmove-lite' ),
				'dashed' => __( 'Dashed', 'woo-thank-you-page-nextmove-lite' ),
				'solid'  => __( 'Solid', 'woo-thank-you-page-nextmove-lite' ),
				'double' => __( 'Double', 'woo-thank-you-page-nextmove-lite' ),
				'none'   => __( 'None', 'woo-thank-you-page-nextmove-lite' ),
			),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Border Width', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_border_width',
			'type'        => 'text_small',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_combine_3_field_middle' ),
			'before'      => '<p class="xlwcty_mt5 xlwcty_mb5">Width (px)</p>',
			'attributes'  => array(
				'type'                   => 'number',
				'min'                    => '0',
				'pattern'                => '\d*',
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Border Color', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_border_color',
			'type'        => 'colorpicker',
			'row_classes' => array( 'xlwcty_hide_label', 'xlwcty_combine_3_field_end' ),
			'before'      => '<p class="xlwcty_mt5 xlwcty_mb5">Color</p>',
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Background', 'thank-you-page-for-woocommerce-nextmove' ),
			'desc'        => __( 'Component background color', 'thank-you-page-for-woocommerce-nextmove' ),
			'id'          => $config['slug'] . '_component_bg',
			'type'        => 'colorpicker',
			'row_classes' => array(),
			'after_row'   => array( 'XLWCTY_Admin_CMB2_Support', 'cmb_after_row_cb' ),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),


	),
);
$config['default']  = array(
	'border_style'       => 'solid',
	'border_width'       => '1',
	'border_color'       => '#d9d9d9',
	'component_bg_color' => '#ffffff',
);

return $config;
