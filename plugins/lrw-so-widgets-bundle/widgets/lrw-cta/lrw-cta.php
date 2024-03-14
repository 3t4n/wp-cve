<?php
/**
 * Widget Name: LRW - Call to Action
 * Description: A call to action widget.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_CTA extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-cta',
		  	__( 'LRW - Call to Action', 'lrw-so-widgets-bundle' ),
		  	array(
				'description' => __( 'Simples call to action.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
		  	array(

		  	),
		  	array(

				'text' => array(
					'type' => 'section',
					'label' => __( 'Texts', 'lrw-so-widgets-bundle' ),
					'fields' => array(

						'title' => array(
							'type' => 'text',
							'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
						),

						'sub_title' => array(
							'type' => 'text',
							'label' => __( 'Subtitle', 'lrw-so-widgets-bundle' ),
						),
				  	)
				),

				'button' => array(
					'type' => 'widget',
					'class' => 'SiteOrigin_Widget_Button_Widget',
					'label' => __( 'Button', 'lrw-so-widgets-bundle' ),
				),

				'design' => array(
					'type' => 'section',
					'label' => __( 'Design', 'lrw-so-widgets-bundle' ),
					'fields' => array(

						'show_border' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Show border?', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Select a color below to work.', 'lrw-so-widgets-bundle' )
						),

						'background_color' => array(
							'type' => 'color',
							'label' => __( 'Background color', 'lrw-so-widgets-bundle' ),
							'default' => '#f9f9f9'
						),

						'title_color' => array(
							'type' => 'color',
							'label' => __( 'Title color', 'lrw-so-widgets-bundle' ),
							'default' => ''
						),

						'text_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
							'default' => ''
						),

						'padding' => array(
							'type' => 'select',
							'label' => __( 'Padding', 'lrw-so-widgets-bundle' ),
							'default' => '2',
							'options' => array(
								'0.8' => __( 'Low', 'lrw-so-widgets-bundle' ),
								'1.8' => __( 'Medium', 'lrw-so-widgets-bundle' ),
								'2.8' => __( 'High', 'lrw-so-widgets-bundle' ),
								'3.8' => __( 'Very high', 'lrw-so-widgets-bundle' ),
							),
						),

					)
				),
			),
			plugin_dir_path( __FILE__ )
		);
	}


	function get_template_name( $instance ) {
		return 'view';
	}

	function get_style_name( $instance ) {
		return 'style';
	}

	function get_less_variables( $instance ) {
		if ( empty( $instance ) ) return array();

		return array(
			'background_color' => $instance['design']['background_color'],
			'title_color' 	   => $instance['design']['title_color'],
			'text_color' 	   => $instance['design']['text_color'],
			'padding' 		   => $instance['design']['padding'] . 'em',
		);
	}
}

siteorigin_widget_register( 'lrw-cta', __FILE__, 'LRW_Widget_CTA' );
