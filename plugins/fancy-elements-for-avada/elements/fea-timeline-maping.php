<?php
/**
 * Fancy timeline element mapping with Avada builder.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Map timeline v1 shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fea_map_timeline_v1_addon_with_fb() {

	// Map settings for parent shortcode.
	fusion_builder_map(
		array(
			'name'          => esc_attr__( 'Fancy Timeline V1', 'fancy-elements-avada' ),
			'shortcode'     => 'fea_fancy_timeline_v1',
			'multi'         => 'multi_element_parent',
			'element_child' => 'fea_fancy_timeline_v1_child',
			'icon'          => 'fa-business-time fas',
			'preview'       => FEA_ADDON_PLUGIN_DIR . 'inc/shortcodes/previews/fea-fancy-timeline-v1-preview.php',
			'preview_id'    => 'fusion-builder-block-module-fea-template-timeline-v1',
			'params'        => array(
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Content for the timeline.', 'fancy-elements-avada' ),
					'param_name'  => 'element_content',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Primary Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Primary color for timeline. ', 'fancy-elements-avada' ),
					'param_name'  => 'primarycolor',
					'value'       => '#65bc7b',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Icons Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the fontawesome icons in the timeline. ', 'fancy-elements-avada' ),
					'param_name'  => 'iconscolor',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the title. ', 'fancy-elements-avada' ),
					'param_name'  => 'titlecolor',
					'value'       => '#000000',
				),

				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Caption Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the caption. ', 'fancy-elements-avada' ),
					'param_name'  => 'captioncolor',
					'value'       => '#65bc7b',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Text Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the content. ', 'fancy-elements-avada' ),
					'param_name'  => 'textcolor',
					'value'       => '#717171',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Select size for the headings.', 'fancy-elements-avada' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'1' => 'H1',
						'2' => 'H2',
						'3' => 'H3',
						'4' => 'H4',
						'5' => 'H5',
						'6' => 'H6',
					),
					'default'     => '2',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Caption Size', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Select size for the captions.', 'fancy-elements-avada' ),
					'param_name'  => 'caption_size',
					'value'       => array(
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
			'name'              => esc_attr__( 'Timeline Element', 'fancy-elements-avada' ),
			'shortcode'         => 'fea_fancy_timeline_v1_child',
			'hide_from_builder' => true,
			'allow_generator'   => true,
			'params'            => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Title of the entry.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_timeline_title',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Caption', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Caption of the entry.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_timeline_caption',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
				array(
					'heading'     => esc_attr__( 'Icon', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Select FontAwesome Icon.', 'fancy-elements-avada' ),
					'value'       => 'fas fa-business-time',
					'type'        => 'iconpicker',
					'param_name'  => 'fea_timeline_icon',
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Content for the entry.', 'fancy-elements-avada' ),
					'param_name'  => 'element_content',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),

			),
		)
	);

}
add_action( 'fusion_builder_before_init', 'fea_map_timeline_v1_addon_with_fb', 11 );
