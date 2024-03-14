<?php
/**
 * Widget Name: LRW - Separator
 * Description: Separator line with text option.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Separator extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-separator',
			__( 'LRW - Separator', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Separator line with text option.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
			array(
			),
			array(

				'title' => array(
					'type' => 'text',
					'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
					'sanitize' => 'title'
				),

				'title_align' => array(
					'type' => 'select',
					'label' => __( 'Title align', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Title location', 'lrw-so-widgets-bundle' ),
					'default' => 'center',
					'options' => array(
						'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
						'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
						'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
					),
				),

				'title_type' => array(
					'type' => 'select',
					'label' => __( 'Title element tag', 'lrw-so-widgets-bundle' ),
					'default' => 'h4',
					'options' => array(
						'p'  => __( 'p', 'lrw-so-widgets-bundle' ),
						'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
						'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
						'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
						'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
						'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
						'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
					),
				),

				'separator_align' => array(
					'type' => 'select',
					'label' => __( 'Separator align', 'lrw-so-widgets-bundle' ),
					'default' => 'center',
					'options' => array(
						'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
						'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
						'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
					),
				),

				'separator_color' => array(
					'type' => 'color',
					'label' => __( 'Separator color', 'lrw-so-widgets-bundle' ),
				),

				'border_style' => array(
					'type' => 'select',
					'label' => __( 'Border style', 'lrw-so-widgets-bundle' ),
					'label' => __( 'Separator border style', 'lrw-so-widgets-bundle' ),
					'default' => 'solid',
					'options' => array(
						'dashed' => __( 'Dashed', 'lrw-so-widgets-bundle' ),
						'dotted' => __( 'Dotted', 'lrw-so-widgets-bundle' ),
						'double' => __( 'Double', 'lrw-so-widgets-bundle' ),
						'shadow' => __( 'Shadow', 'lrw-so-widgets-bundle' ),
						'solid'  => __( 'Solid', 'lrw-so-widgets-bundle' ),
					),
				),

				'border_width' => array(
					'type' => 'select',
					'label' => __( 'Border width', 'lrw-so-widgets-bundle' ),
					'default' => '1px',
					'options' => array(
						'1' => __( '1px', 'lrw-so-widgets-bundle' ),
						'2' => __( '2px', 'lrw-so-widgets-bundle' ),
						'3' => __( '3px', 'lrw-so-widgets-bundle' ),
						'4' => __( '4px', 'lrw-so-widgets-bundle' ),
						'5' => __( '5px', 'lrw-so-widgets-bundle' ),
						'6' => __( '6px', 'lrw-so-widgets-bundle' ),
						'7' => __( '7px', 'lrw-so-widgets-bundle' ),
						'8' => __( '8px', 'lrw-so-widgets-bundle' ),
						'9' => __( '9px', 'lrw-so-widgets-bundle' ),
						'10' => __( '10px', 'lrw-so-widgets-bundle' ),
					),
				),

				'sep_width' => array(
					'type'        => 'slider',
					'label' => __( 'Element width (%)', 'lrw-so-widgets-bundle' ),
					'min'         => 10,
					'max'         => 100,
					'default'     => 80,
					'integer'     => true,
				),

				'icon_active' => array(
					'type' => 'radio',
					'default' => 'no',
					'label' => __( 'Add icon', 'lrw-so-widgets-bundle' ),
					'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'icon_active' )
                    ),
                    'options' => array(
						'yes' => __( 'Yes', 'lrw-so-widgets-bundle' ),
						'no' => __( 'No', 'lrw-so-widgets-bundle' )
					),
				),

				'icon_options' => array(
					'type' => 'section',
					'label' => __( 'Icon', 'lrw-so-widgets-bundle' ),
					'state_handler' => array(
						'icon_active[yes]' => array( 'show' ),
						'_else[icon_active]' => array( 'hide' ),
					),
					'hide' => true,
					'fields' => array(
						'icon' => array(
							'type' => 'icon',
							'label' => __( 'Icon', 'lrw-so-widgets-bundle' ),
						),

						'icon_color' => array(
							'type' => 'color',
							'label' => __( 'Icon color', 'lrw-so-widgets-bundle' ),
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
					),
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

	/**
	 * The less variables to control the design of the slider
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	function get_less_variables( $instance ) {
		if ( empty( $instance ) ) return;

		return array(
			'separator_color'	=> $instance['separator_color'],
			'shape_color'		=> $instance['icon_options']['shape_color'],
			'icon_color'		=> $instance['icon_options']['icon_color'],
		);
	}

}

siteorigin_widget_register( 'lrw-separator', __FILE__, 'LRW_Widget_Separator' );
