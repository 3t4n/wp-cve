<?php
/**
 * Widget Name: LRW - Slider
 * Description: A simple slider images widget. It can be used as a slider or carrousel.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Slider extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-slider',
			__( 'LRW - Slider', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Images slider or carrousel.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
			array(

			),
			array(

				'images' => array(
					'type' => 'repeater',
					'label' => __( 'Images', 'lrw-so-widgets-bundle' ),
					'item_name' => __( 'Image', 'lrw-so-widgets-bundle' ),
					'item_label' => array(
						'selector' => "[id*='images-title']",
						'update_event' => 'change',
						'value_method' => 'val'
					),
					'fields' => array(
						'image_slider' => array(
							'type' => 'media',
							'library' => 'image',
							'label' => __( 'Image', 'lrw-so-widgets-bundle' ),
						),

						'title' => array(
							'type' => 'text',
							'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
							'sanitize' => 'title',
							'description' => __( 'Title for "alt" and "title" images attributes (optional).', 'lrw-so-widgets-bundle' )
						),

						'url' => array(
							'type' => 'link',
							'label' => __( 'Destination URL (optional)', 'lrw-so-widgets-bundle' ),
						),

						'new_window' => array(
							'type' => 'checkbox',
							'default' => false,
							'label' => __( 'Open in a new window', 'lrw-so-widgets-bundle' ),
						),
					),
				),

				'slidemode' => array(
					'type' => 'select',
					'label' => __( 'Type of transition', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Type of transition between slides.', 'lrw-so-widgets-bundle' ),
					'default' => 'horizontal',
					'options' => array(
						'horizontal' => __( 'Horizontal', 'lrw-so-widgets-bundle' ),
						'vertical' => __( 'Vertical', 'lrw-so-widgets-bundle' ),
						'fade' => __( 'Fade', 'lrw-so-widgets-bundle' ),
					)
				),

				'slidespeed' => array(
					'type' => 'number',
					'label' => __( 'Speed of slideshow', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Slide transition duration, in milliseconds.', 'lrw-so-widgets-bundle' ),
					'default' => 500,
				),

				'captions' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __( 'Include image captions', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Include image captions. Captions are derived from the images title attribute.', 'lrw-so-widgets-bundle' ),
				),

				'auto' => array(
					'type' => 'select',
					'label' => __( 'Animate slide automatically', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Slides will automatically transition.', 'lrw-so-widgets-bundle' ),
					'default' => 'true',
					'options' => array(
						'true' => __( 'Yes', 'lrw-so-widgets-bundle' ),
						'false' => __( 'No', 'lrw-so-widgets-bundle' ),
					)
				),

				'pausehover' => array(
					'type' => 'select',
					'label' => __( 'Pause on hover', 'lrw-so-widgets-bundle' ),
					'description' => __( 'Auto show will pause when mouse hovers over slider.', 'lrw-so-widgets-bundle' ),
					'default' => 'true',
					'options' => array(
						'true' => __( 'Yes', 'lrw-so-widgets-bundle' ),
						'false' => __( 'No', 'lrw-so-widgets-bundle' ),
					)
				),

				'slidetype' => array(
					'type' => 'radio',
					'default' => 'normal',
					'label' => __( 'Slider type', 'lrw-so-widgets-bundle' ),
					'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'slidetype' )
                    ),
					'options' => array(
						'normal' => __( 'Normal', 'lrw-so-widgets-bundle' ),
						'carousel' => __( 'Carousel', 'lrw-so-widgets-bundle' )
					)
				),

				'slider_carousel'      => array(
					'type'        => 'section',
					'label'       => __( 'Carousel settings', 'lrw-so-widgets-bundle' ),
					'state_handler' => array(
						'slidetype[carousel]' => array( 'show' ),
						'_else[slidetype]' => array( 'hide' ),
					),
					'hide'        => true,
					'description' => __( 'Set up carousel slideshow.', 'lrw-so-widgets-bundle' ),
					'fields'      => array(
						'slidewidth' => array(
							'type' => 'number',
							'label' => __( 'Width', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Width of individual carousel items, including horizontal borders and padding.', 'lrw-so-widgets-bundle' ),
							'default' => 160,
						),

						'minslides' => array(
							'type' => 'number',
							'label' => __( 'Minimum to show', 'lrw-so-widgets-bundle' ),
							'description' => __( 'The minimum number of slides to be shown.', 'lrw-so-widgets-bundle' ),
							'default' => 1,
						),

						'maxslides' => array(
							'type' => 'number',
							'label' => __( 'Maximum to show', 'lrw-so-widgets-bundle' ),
							'description' => __( 'The maximum number of slides to be shown.', 'lrw-so-widgets-bundle' ),
							'default' => 1,
						),

						'slidemargin' => array(
							'type' => 'number',
							'label' => __( 'Slide margin', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Margin between each slide.', 'lrw-so-widgets-bundle' ),
							'default' => 10,
						),

						'moveslides' => array(
							'type' => 'number',
							'label' => __( 'Number to move', 'lrw-so-widgets-bundle' ),
							'description' => __( 'The number of slides to move on transition.', 'lrw-so-widgets-bundle' ),
							'default' => 1,
						),
					)

				),

			),

			plugin_dir_path( __FILE__ )
		);
	}


	function get_style_name( $instance ) {
		return false;
	}

	function get_template_name( $instance ) {
		return 'view';
	}

	/**
	 * Enqueue the slider scripts
	 */
	function initialize() {
	    $this->register_frontend_styles(
	        array(
	            array( 'bxslider-css', siteorigin_widget_get_plugin_dir_url( 'lrw-slider' ) . 'assets/css/jquery.bxslider.css', array(), LRW_BUNDLE_VERSION )
	        )
	    );

	    $this->register_frontend_scripts(
	        array(
	            array( 'bxslider-js', siteorigin_widget_get_plugin_dir_url( 'lrw-slider' ) . 'assets/js/jquery.bxslider.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION ),
	            array( 'slider-init', siteorigin_widget_get_plugin_dir_url( 'lrw-slider' ) . 'assets/js/slider-init.js', array( 'jquery' ), LRW_BUNDLE_VERSION )
	        )
	    );
	}

}

siteorigin_widget_register( 'lrw-slider', __FILE__, 'LRW_Widget_Slider' );
