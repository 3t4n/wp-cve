<?php
/**
 * Fancy tabs element mapping with Avada builder.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Shortcode mapping with builder.
 *
 * @since 1.0
 */
function fea_map_addon_with_fb() {

	// Map settings for parent shortcode.
	fusion_builder_map(
		array(
			'name'          => esc_attr__( 'Fancy Tabs', 'fancy-elements-avada' ),
			'shortcode'     => 'fea_fancy_tabs',
			'multi'         => 'multi_element_parent',
			'element_child' => 'fea_fancy_tab',
			'icon'          => 'fas fa-columns',
			'preview'       => FEA_ADDON_PLUGIN_DIR . 'inc/shortcodes/previews/fea-fancy-tabs-preview.php',
			'preview_id'    => 'fusion-builder-block-module-fea-template-fancy-tabs',
			'params'        => array(
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Enter some content.', 'fancy-elements-avada' ),
					'param_name'  => 'element_content',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Inactive Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls color for inactive tabs. ', 'fancy-elements-avada' ),
					'param_name'  => 'inactivecolor',
					'value'       => '#292d32',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Active Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls color for active tab. ', 'fancy-elements-avada' ),
					'param_name'  => 'activecolor',
					'value'       => '#81c893',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Upper Arrow Diamond Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls the color of top part of arrow. ', 'fancy-elements-avada' ),
					'param_name'  => 'arrowupcolor',
					'value'       => '#292d32',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Down Arrow Diamond Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls the color of bottom part of arrow. ', 'fancy-elements-avada' ),
					'param_name'  => 'arrowdowncolor',
					'value'       => '#81c893',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls the color of headings. ', 'fancy-elements-avada' ),
					'param_name'  => 'headingcolor',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Select html tag for the headings.', 'fancy-elements-avada' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'1' => 'H1',
						'2' => 'H2',
						'3' => 'H3',
						'4' => 'H4',
						'5' => 'H5',
						'6' => 'H6',
					),
					'default'     => '3',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Caption Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls the color of captions. ', 'fancy-elements-avada' ),
					'param_name'  => 'captioncolor',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Caption Size', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Select html tag for the captions.', 'fancy-elements-avada' ),
					'param_name'  => 'caption_size',
					'value'       => array(
						'0' => 'p',
						'1' => 'H1',
						'2' => 'H2',
						'3' => 'H3',
						'4' => 'H4',
						'5' => 'H5',
						'6' => 'H6',
					),
					'default'     => '5',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Tab Hover Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Controls the hover color of tabs. ', 'fancy-elements-avada' ),
					'param_name'  => 'hovercolor',
					'value'       => '#81c893',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Border Radius for Tab Icon Image', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Use value in % for radius, default value is 0.', 'fancy-elements-avada' ),
					'param_name'  => 'border',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fancy-elements-avada' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Active Tab#', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Enter no. of tab, you want to be active by default.', 'fancy-elements-avada' ),
					'param_name'  => 'active_tab',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fancy-elements-avada' ),
					'default'     => 1,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'fancy-elements-avada' ),
					'param_name'  => 'class',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fancy-elements-avada' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'fancy-elements-avada' ),
					'param_name'  => 'id',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fancy-elements-avada' ),
				),
			),
		)
	);

	// Map settings for child shortcode.
	fusion_builder_map(
		array(
			'name'              => esc_attr__( 'Fancy Tab', 'fancy-elements-avada' ),
			'shortcode'         => 'fea_fancy_tab',
			'hide_from_builder' => true,
			'allow_generator'   => true,
			'params'            => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Tab Title', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Title of the Tab.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_tab_title',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Tab Caption Line', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Caption of the Tab.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_tab_caption',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
				array(
					'heading'     => esc_attr__( 'Icon Image', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Upload the icon image you would like to use for this Tab.', 'fancy-elements-avada' ),
					'value'       => '',
					'type'        => 'upload',
					'param_name'  => 'fea_tab_icon_image',
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Tab Content', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Add content for the Tab.', 'fancy-elements-avada' ),
					'param_name'  => 'element_content',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),

			),
		)
	);

}
add_action( 'fusion_builder_before_init', 'fea_map_addon_with_fb', 11 );
