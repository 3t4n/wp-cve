<?php
/**
 * Fancy testimonial element mapping with Avada builder.
 *
 * @author    WP Square
 * @package   fancy-elements-avada
 */

/**
 * Map testimonial shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fea_map_testimonial_addon_with_fb() {

	// Map settings for parent shortcode.
	fusion_builder_map(
		array(
			'name'          => esc_attr__( 'Fancy Testimonial', 'fancy-elements-avada' ),
			'shortcode'     => 'fea_fancy_testimonial',
			'multi'         => 'multi_element_parent',
			'element_child' => 'fea_fancy_testimonial_child',
			'icon'          => 'far fa-comments',
			'preview'       => FEA_ADDON_PLUGIN_DIR . 'inc/shortcodes/previews/fea-fancy-testimonial-preview.php',
			'preview_id'    => 'fusion-builder-block-module-fea-template-testimonial',
			'params'        => array(
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Content for the testimonial.', 'fancy-elements-avada' ),
					'param_name'  => 'element_content',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Primary Background Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Primary Background color for testimonial item. ', 'fancy-elements-avada' ),
					'param_name'  => 'primarybgcolor',
					'value'       => '#65bc7b',
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Caption Text Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Text Color for the Caption. ', 'fancy-elements-avada' ),
					'param_name'  => 'captioncolor',
					'value'       => '#2f3237',
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
					'heading'     => esc_attr__( 'Text Color', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Color for the content. ', 'fancy-elements-avada' ),
					'param_name'  => 'textcolor',
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
					'default'     => '2',
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
			'name'              => esc_attr__( 'Testimonial Element', 'fancy-elements-avada' ),
			'shortcode'         => 'fea_fancy_testimonial_child',
			'hide_from_builder' => true,
			'allow_generator'   => true,
			'params'            => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Title', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Title of the entry.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_testimonial_title',
					'value'       => 'Your Content Goes Here',
					'placeholder' => true,
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_attr__( 'Caption', 'fancy-elements-avada' ),
					'param_name' => 'fea_testimonial_caption',
					'value'      => '',
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image', 'fancy-elements-avada' ),
					'description' => esc_attr__( 'Upload an image that will show up in the testimonial.', 'fancy-elements-avada' ),
					'param_name'  => 'fea_testimonial_image',
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
add_action( 'fusion_builder_before_init', 'fea_map_testimonial_addon_with_fb', 11 );
