<?php
/**
 * Fancy timeline 2 element mapping with Avada builder.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Map timeline v2 shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fea_map_timeline_v2_addon_with_fb() {

	// Map settings for parent shortcode.
	fusion_builder_map(
		array(
			'name'          => esc_attr__( 'Fancy Timeline V2', 'fancy-elements-avada' ),
			'shortcode'     => 'fea_fancy_timeline_v2',
			'multi'         => 'multi_element_parent',
			'element_child' => 'fea_fancy_timeline_v2_child',
			'icon'          => 'fa-business-time fas',
			'preview'       => FEA_ADDON_PLUGIN_DIR . 'inc/shortcodes/previews/fea-fancy-timeline-v2-preview.php',
			'preview_id'    => 'fusion-builder-block-module-fea-template-timeline-v2',
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
					'heading'     => esc_attr__( 'Background Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Background color for timeline item. ', 'fancy-elements-avada' ),
					'param_name'  => 'bgcolor',
					'value'       => '#2f3237',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Date Background Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Background Color for the date. ', 'fancy-elements-avada' ),
					'param_name'  => 'datebgcolor',
					'value'       => '',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Date Text Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Text Color for the date. ', 'fancy-elements-avada' ),
					'param_name'  => 'datecolor',
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
					'heading'     => esc_attr__( 'Heading Color with Image', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the title when used with Image. ', 'fancy-elements-avada' ),
					'param_name'  => 'imagetitlecolor',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Text Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the content. ', 'fancy-elements-avada' ),
					'param_name'  => 'textcolor',
					'value'       => '#ffffff',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Button Background Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Background Color for the Read More. ', 'fancy-elements-avada' ),
					'param_name'  => 'readmorebg',
					'value'       => '',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Read More Button Text Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the read more button. ', 'fancy-elements-avada' ),
					'param_name'  => 'readmoretextcolor',
					'value'       => '#ffffff',
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
			'shortcode'         => 'fea_fancy_timeline_v2_child',
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
					'type'       => 'textfield',
					'heading'    => esc_attr__( 'Date', 'fancy-elements-avada' ),
					'param_name' => 'fea_timeline_date',
					'value'      => '',
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Upload an image that will show up in the timeline.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_timeline_image',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Read More Text', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Text for the read more button.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_timeline_rm_text',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Read More Link', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'URL for the read more button link.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_timeline_rm_link',
					'value'       => '',
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
add_action( 'fusion_builder_before_init', 'fea_map_timeline_v2_addon_with_fb', 11 );
