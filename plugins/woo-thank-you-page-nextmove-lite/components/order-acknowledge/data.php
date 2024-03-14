<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_order';
$config['title']    = __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' );
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                     => $config['slug'],
	'xlwcty_accordion_head'  => 'Order',
	'xlwcty_accordion_title' => $config['title'],
	'xlwcty_icon'            => 'xlwcty-fa xlwcty-fa-check-circle-o',
	'position'               => 1,
	'fields'                 => array(
		array(
			'name'                       => __( 'Enable', 'woo-thank-you-page-nextmove-lite' ),
			'id'                         => $config['slug'] . '_enable',
			'type'                       => 'xlwcty_switch',
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
			'name'        => __( 'Icon', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_icon',
			'type'        => 'radio_inline',
			'row_classes' => array( 'xlwcty_no_border ' ),
			'options'     => array(
				'built_in' => __( 'Built-in', 'woo-thank-you-page-nextmove-lite' ),
				'custom'   => __( 'Custom', 'woo-thank-you-page-nextmove-lite' ),
				'none'     => __( 'None', 'woo-thank-you-page-nextmove-lite' ),
			),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'         => __( 'Built In', 'woo-thank-you-page-nextmove-lite' ),
			'id'           => $config['slug'] . '_built_in',
			'type'         => 'select',
			'row_classes'  => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_select_small', 'xlwcty_combine_2_field_start', 'xlwcty_pt0' ),
			'before'       => '<p>Icon</p>',
			'before_field' => '<div class="xlwcty_icon_preview_before" >',
			'after_field'  => '<div class="xlwcty_icon_preview"><i class="xlwcty_custom_icon"></i></div></div>',
			'options'      => array(
				'xlwcty-fa-check'         => __( 'Checkmark', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-smile-o'       => __( 'Smiley', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-thumbs-up'     => __( 'Thumbs Up', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-clock-o'       => __( 'Clock', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-bell'          => __( 'Bell', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-bullhorn'      => __( 'Horn', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-diamond'       => __( 'Diamond', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-flash'         => __( 'Flash', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-globe'         => __( 'Globe', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-gift'          => __( 'Gift', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-heart'         => __( 'Heart', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-lightbulb-o'   => __( 'Bulb', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-flag'          => __( 'Flag', 'woo-thank-you-page-nextmove-lite' ),
				'xlwcty-fa-shopping-cart' => __( 'Cart', 'woo-thank-you-page-nextmove-lite' ),
			),
			'attributes'   => array(
				'class'                         => 'cmb2_select xlwcty_icon_select',
				'data-conditional-id'           => $config['slug'] . '_enable',
				'data-conditional-value'        => '1',
				'data-xlwcty-conditional-id'    => $config['slug'] . '_icon',
				'data-xlwcty-conditional-value' => 'built_in',
			),
		),
		array(
			'name'        => __( 'Icon Color', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_built_in_color',
			'type'        => 'colorpicker',
			'row_classes' => array( 'xlwcty_combine_2_field_end', 'xlwcty_no_border', 'xlwcty_pt0' ),
			'before'      => '<p>Color</p>',
			'attributes'  => array(
				'data-conditional-id'           => $config['slug'] . '_enable',
				'data-conditional-value'        => '1',
				'data-xlwcty-conditional-id'    => $config['slug'] . '_icon',
				'data-xlwcty-conditional-value' => 'built_in',
			),
		),
		array(
			'name'        => __( 'Custom', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_icon_custom',
			'type'        => 'file',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
			'before'      => '<p>Icon</p>',
			'options'     => array(
				'url' => false,
			),
			'text'        => array(
				'add_upload_file_text' => 'Add/ Update Icon',
			),
			'attributes'  => array(
				'data-conditional-id'           => $config['slug'] . '_enable',
				'data-conditional-value'        => '1',
				'data-xlwcty-conditional-id'    => $config['slug'] . '_icon',
				'data-xlwcty-conditional-value' => 'custom',
			),
		),
		array(
			'name'        => __( 'Heading', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading1',
			'type'        => 'text',
			'desc'        => '<a href="javascript:void(0);" onclick="xlwcty_show_tb(\'Merge Tags\',\'xlwcty_merge_tags_invenotry_bar_help\');">Dynamic merge tags list</a>',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_border_top' ),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Heading Font Size', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading1_font_size',
			'type'        => 'text_small',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_combine_2_field_start', 'xlwcty_pt0' ),
			'before'      => '<p>Font Size (px)</p>',
			'attributes'  => array(
				'type'                   => 'number',
				'min'                    => '0',
				'pattern'                => '\d*',
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Heading Color', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading1_color',
			'type'        => 'colorpicker',
			'row_classes' => array( 'xlwcty_hide_label', 'xlwcty_combine_2_field_end', 'xlwcty_pt0' ),
			'before'      => '<p>Color</p>',
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Sub Heading', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading2',
			'type'        => 'text',
			'desc'        => '<a href="javascript:void(0);" onclick="xlwcty_show_tb(\'Merge Tags\',\'xlwcty_merge_tags_invenotry_bar_help\');">Dynamic merge tags list</a>',
			'row_classes' => array( 'xlwcty_no_border' ),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Sub Heading Font Size', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading2_font_size',
			'type'        => 'text_small',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_combine_2_field_start', 'xlwcty_pt0' ),
			'before'      => '<p>Font Size (px)</p>',
			'attributes'  => array(
				'type'                   => 'number',
				'min'                    => '0',
				'pattern'                => '\d*',
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Sub Heading Color', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading2_color',
			'type'        => 'colorpicker',
			'row_classes' => array( 'xlwcty_hide_label', 'xlwcty_combine_2_field_end', 'xlwcty_pt0' ),
			'before'      => '<p>Color</p>',
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable',
				'data-conditional-value' => '1',
			),
			'after_row'   => array( 'XLWCTY_Admin_CMB2_Support', 'cmb_after_row_cb' ),
		),
	),
);
$config['default']  = array(
	'icon'               => 'built_in',
	'icon_builtin'       => 'xlwcty-fa-check',
	'icon_builtin_color' => '#1291ff',
	'icon_custom'        => '',
	'heading'            => __( 'Order', 'woo-thank-you-page-nextmove-lite' ) . ' #{{order_no}}',
	'heading_font_size'  => '20',
	'heading_color'      => '#7e7e7e',
	'heading2'           => __( 'Thank you', 'woo-thank-you-page-nextmove-lite' ) . ' {{customer_first_name}}',
	'heading2_font_size' => '28',
	'heading2_color'     => '#000000',
);

return $config;
