<?php
/**
 * Widget Name: LRW - Feature
 * Description: Displays a block of attributes with icons and texts.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Feature extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-feature',
			__( 'LRW - Feature', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Displays a block of attributes with icons and texts.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
			array(),
			array(
				'icon_settings' => array(
					'type' => 'section',
					'label' => __( 'Icon', 'lrw-so-widgets-bundle' ),
					'item_name' => __( 'Icon', 'lrw-so-widgets-bundle' ),
					'fields' => array(
		                'icon_align' => array(
							'type' => 'select',
							'label' => __( 'Icon align', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),

						'shape_format' => array(
							'type' => 'select',
							'label' => __( 'Shape type', 'lrw-so-widgets-bundle' ),
							'options' => array(
								'none' => __( 'None', 'lrw-so-widgets-bundle' ),
								'circle' => __( 'Circle', 'lrw-so-widgets-bundle' ),
								'square' => __( 'Square', 'lrw-so-widgets-bundle' ),
								'rounded' => __( 'Rounded', 'lrw-so-widgets-bundle' ),
								'outline-circle' => __( 'Outline circle', 'lrw-so-widgets-bundle' ),
								'outline-square' => __( 'Outline square', 'lrw-so-widgets-bundle' ),
								'outline-rounded' => __( 'Outline rounded', 'lrw-so-widgets-bundle' ),
							),
						),

						'shape_color' => array(
							'type' => 'color',
							'label' => __( 'Shape color', 'lrw-so-widgets-bundle' ),
						),

						'icon_type' => array(
		                    'type' => 'select',
		                    'label' => __( 'Select icon type', 'lrw-so-widgets-bundle' ),
		                    'default' => 'blank',
		                    'state_emitter' => array(
		                        'callback' => 'select',
		                        'args' => array( 'icon_type' )
		                    ),
		                    'options' => array(
		                        'blank' => __( 'Select option', 'lrw-so-widgets-bundle' ),
		                        'type_icon' => __( 'Icon', 'lrw-so-widgets-bundle' ),
		                        'type_image' => __( 'Icon image', 'lrw-so-widgets-bundle' ),
		                    )
		                ),

		                'icon_size' => array(
		                    'type' => 'select',
							'label' => __( 'Icon size', 'lrw-so-widgets-bundle' ),
		                    'default' => '4x',
							'options' => array(
								'lg' => __( 'Mini', 'lrw-so-widgets-bundle' ),
								'2x' => __( 'Small', 'lrw-so-widgets-bundle' ),
								'3x' => __( 'normal', 'lrw-so-widgets-bundle' ),
								'4x' => __( 'Large', 'lrw-so-widgets-bundle' ),
								'5x' => __( 'Extra large', 'lrw-so-widgets-bundle' ),
							),
		                ),

		                'icon_position' => array(
							'type' => 'select',
							'label' => __( 'Icon position', 'lrw-so-widgets-bundle' ),
							'default' => 'top',
							'options' => array(
								'top' => __( 'Top', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),

						// Icon
						'type_icon_section' => array(
							'type' => 'section',
							'label' => __( 'Icon', 'lrw-so-widgets-bundle' ),
							'hide' => false,
		                    'state_handler' => array(
		                        'icon_type[type_icon]' => array( 'show' ),
		                        '_else[icon_type]' => array( 'hide' )
		                    ),
							'fields' => array(
								'icon' => array(
									'type' => 'icon',
									'label' => __( 'Icon', 'lrw-so-widgets-bundle' ),
								),

								'icon_color' => array(
									'type' => 'color',
									'label' => __( 'Icon color', 'lrw-so-widgets-bundle' ),
								),
							)
						),

		                // Icon image
		                'type_image_section' => array(
		                    'type' => 'section',
		                    'label' => __( 'Icon image' , 'lrw-so-widgets-bundle' ),
		                    'hide' => false,
		                    'state_handler' => array(
		                        'icon_type[type_image]' => array( 'show' ),
		                        '_else[icon_type]' => array( 'hide' )
		                    ),
		                    'fields' => array(
		                        'image' => array(
									'type' => 'media',
									'library' => 'image',
									'label' => __( 'Image', 'lrw-so-widgets-bundle' ),
									'choose' => __( 'Choose image', 'lrw-so-widgets-bundle' ),
		                            'update' => __( 'Select image', 'lrw-so-widgets-bundle' ),
									'description' => __( 'Use your own icon image.', 'lrw-so-widgets-bundle' ),
								),

								'image_size' => array(
									'type' => 'select',
									'label' => __( 'Image size', 'lrw-so-widgets-bundle' ),
									'options' => array(
										'full' => __( 'Full', 'lrw-so-widgets-bundle' ),
										'large' => __( 'Large', 'lrw-so-widgets-bundle' ),
										'medium' => __( 'Medium', 'lrw-so-widgets-bundle' ),
										'thumb' => __( 'Thumbnail', 'lrw-so-widgets-bundle' ),
									),
								),

								'image_padding' => array(
									'type' => 'measurement',
									'label' => __( 'Padding', 'lrw-so-widgets-bundle' ),
									'default' => '0em',
								),

								'image_overflow' => array(
									'type' => 'checkbox',
									'default' => true,
									'label' => __( 'Allow the image exceeds the container', 'lrw-so-widgets-bundle' ),
								),
		                    )
		                ),
					)
				),

				'heading' => array(
					'type' => 'section',
					'label' => __( 'Heading', 'lrw-so-widgets-bundle' ),
					'description' => '',
					'hide' => true,
					'fields' => array(
						'heading_text' => array(
							'type' => 'text',
							'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
						),

						'heading_type' => array(
							'type' => 'select',
							'label' => __( 'Element tag', 'lrw-so-widgets-bundle' ),
							'options' => array(
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
							),
						),

						'heading_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
						),

						'heading_align' => array(
							'type' => 'select',
							'label' => __( 'Heading align', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),

						'margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '10px',
						),
					)
				),

				'content' => array(
					'type' => 'section',
					'label' => __( 'Content', 'lrw-so-widgets-bundle' ),
					'description' => '',
					'hide' => true,
					'fields' => array(
						'text' => array(
							'type' => 'tinymce',
							'label' => __( 'Text', 'lrw-so-widgets-bundle' ),
							'sanitize' => 'text',
						),

						'text_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
						),

						'text_align' => array(
							'type' => 'select',
							'label' => __( 'Content align', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),
					)
				),

			),

			plugin_dir_path( __FILE__ )
		);
	}

	function get_style_name( $instance ) {
        return 'style';
    }

	function get_template_name( $instance ) {
		return 'view';
	}

    function get_less_variables( $instance ) {
		if ( empty( $instance ) ) return;

		return array(
			'icon_align' 	=> $instance['icon_settings']['icon_align'],
			'shape_color'	=> $instance['icon_settings']['shape_color'],
			'icon_color'	=> $instance['icon_settings']['type_icon_section']['icon_color'],
			'image_padding'	=> $instance['icon_settings']['type_image_section']['image_padding'],
			'heading_color' => $instance['heading']['heading_color'],
			'heading_align' => $instance['heading']['heading_align'],
			'margin_top'	=> $instance['heading']['margin_top'],
			'margin_bottom'	=> $instance['heading']['margin_bottom'],
			'text_color' 	=> $instance['content']['text_color'],
			'text_align'	=> $instance['content']['text_align']
		);
	}

	function get_template_variables( $instance, $args ) {
        return array(
            'shape_format' 		=> $instance['icon_settings']['shape_format'],
			'shape_color' 		=> $instance['icon_settings']['shape_color'],
			'icon_type' 		=> $instance['icon_settings']['icon_type'],
			'icon' 				=> $instance['icon_settings']['type_icon_section']['icon'],
			'icon_color' 		=> $instance['icon_settings']['type_icon_section']['icon_color'],
			'icon_size' 		=> $instance['icon_settings']['icon_size'],
			'icon_position'		=> $instance['icon_settings']['icon_position'],
			'image'  	   		=> $instance['icon_settings']['type_image_section']['image'],
			'image_size'		=> $instance['icon_settings']['type_image_section']['image_size'],
			'image_padding'		=> $instance['icon_settings']['type_image_section']['image_padding'],
			'image_overflow'	=> $instance['icon_settings']['type_image_section']['image_overflow'],
			'heading_text'  	=> $instance['heading']['heading_text'],
			'heading_type'  	=> $instance['heading']['heading_type'],
			'text' 		   		=> $instance['content']['text']
        );
    }
}

siteorigin_widget_register( 'lrw-feature', __FILE__, 'LRW_Widget_Feature' );
