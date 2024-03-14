<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_text';
$config['title']    = 'HTML';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                     => $config['slug'] . '_1',
	'position'               => 25,
	'xlwcty_accordion_title' => $config['title'],
	'xlwcty_icon'            => 'xlwcty-fa xlwcty-fa-code',
	'fields'                 => array(
		array(
			'name'                       => __( 'Enable', 'woo-thank-you-page-nextmove-lite' ),
			'id'                         => $config['slug'] . '_enable_1',
			'type'                       => 'xlwcty_switch',
			'row_classes'                => array( 'xlwcty_is_enable' ),
			'label'                      => array(
				'on'  => __( 'Yes', 'woo-thank-you-page-nextmove-lite' ),
				'off' => __( 'No', 'woo-thank-you-page-nextmove-lite' ),
			),
			'before_row'                 => array( 'XLWCTY_Admin_CMB2_Support', 'cmb_before_row_cb' ),
			'xlwcty_accordion_title'     => $config['title'] . ' 1',
			'xlwcty_accordion_index'     => '1',
			'xlwcty_component'           => $config['slug'],
			'xlwcty_is_accordion_opened' => false,
			'after'                      => include_once __DIR__ . '/help.php',
		),
		array(
			'name'        => __( 'Heading', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading_1',
			'type'        => 'text',
			'row_classes' => array( 'xlwcty_no_border' ),
			'desc'        => '<a href="javascript:void(0);" onclick="xlwcty_show_tb(\'Merge Tags\',\'xlwcty_merge_tags_invenotry_bar_help\');">Dynamic merge tags list</a>',
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Heading font size', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading_font_size_1',
			'type'        => 'text_small',
			'row_classes' => array( 'xlwcty_hide_label', 'xlwcty_pt0', 'xlwcty_combine_2_field_start' ),
			'before'      => '<p>Font Size (px)</p>',
			'attributes'  => array(
				'type'                   => 'number',
				'min'                    => '0',
				'pattern'                => '\d*',
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Heading alignment', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_heading_alignment_1',
			'type'        => 'select',
			'row_classes' => array( 'xlwcty_hide_label', 'xlwcty_pt0', 'xlwcty_combine_2_field_end' ),
			'before'      => '<p>Alignment</p>',
			'options'     => array(
				'left'   => __( 'Left', 'woo-thank-you-page-nextmove-lite' ),
				'center' => __( 'Center', 'woo-thank-you-page-nextmove-lite' ),
				'right'  => __( 'Right', 'woo-thank-you-page-nextmove-lite' ),
			),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'            => __( 'Content Editor', 'woo-thank-you-page-nextmove-lite' ),
			'id'              => $config['slug'] . '_editor_1',
			'row_classes'     => array( '' ),
			'type'            => 'wysiwyg',
			'before_row'      => array( 'XLWCTY_Admin_CMB2_Support', 'before_wysiwyg' ),
			'after_row'       => array( 'XLWCTY_Admin_CMB2_Support', 'after_wysiwyg' ),
			'desc'            => '<a href="javascript:void(0);" onclick="xlwcty_show_tb(\'Merge Tags\',\'xlwcty_merge_tags_invenotry_bar_help\');">Dynamic merge tags list</a>',
			'options'         => array(
				'wpautop'       => true,
				'media_buttons' => false,
				'textarea_rows' => 10,
				'tabindex'      => '',
				'editor_css'    => '',
				'editor_class'  => '',
				'teeny'         => false,
				'dfw'           => false,
				'tinymce'       => true,
				'quicktags'     => true,
			),
			'sanitization_cb' => false,
			'attributes'      => array(
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),

		array(
			'name'        => __( 'Border', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_border_style_1',
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
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Border Width', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_border_width_1',
			'type'        => 'text_small',
			'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_combine_3_field_middle' ),
			'before'      => '<p class="xlwcty_mt5 xlwcty_mb5">Width (px)</p>',
			'attributes'  => array(
				'type'                   => 'number',
				'min'                    => '0',
				'pattern'                => '\d*',
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Border Color', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_border_color_1',
			'type'        => 'colorpicker',
			'row_classes' => array( 'xlwcty_hide_label', 'xlwcty_combine_3_field_end' ),
			'before'      => '<p class="xlwcty_mt5 xlwcty_mb5">Color</p>',
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
		),
		array(
			'name'        => __( 'Background', 'woo-thank-you-page-nextmove-lite' ),
			'desc'        => __( 'Component background color', 'woo-thank-you-page-nextmove-lite' ),
			'id'          => $config['slug'] . '_component_bg_1',
			'type'        => 'colorpicker',
			'row_classes' => array(),
			'attributes'  => array(
				'data-conditional-id'    => $config['slug'] . '_enable_1',
				'data-conditional-value' => '1',
			),
			'after_row'   => array( 'XLWCTY_Admin_CMB2_Support', 'cmb_after_row_cb' ),
		),
	),
);

$config['default'] = array(
	'heading'            => '',
	'heading_font_size'  => '20',
	'heading_alignment'  => 'left',
	'html_content'       => '',
	'border_style'       => 'solid',
	'border_width'       => '1',
	'border_color'       => '#d9d9d9',
	'component_bg_color' => '#ffffff',
);

return $config;
