<?php
/**
 * Widget Name: LRW - Promote box
 * Description: Promote box with image option.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Promote_Box extends SiteOrigin_Widget {

	protected $buttons = array();

	function __construct() {
		parent::__construct(
			'lrw-promote-box',
			__( 'LRW - Promote box', 'lrw-so-widgets-bundle' ),
			array(
				'description' => __( 'Promote box with image option.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
			array(
			),
			array(

				'promote_background' => array(
					'type' => 'section',
					'label' => __( 'Image', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(

						'background' => array(
					        'type' => 'media',
					        'label' => __( 'Photo', 'lrw-so-widgets-bundle' ),
					        'choose' => __( 'Sets image', 'lrw-so-widgets-bundle' ),
		                    'update' => __( 'Select image', 'lrw-so-widgets-bundle' ),
					        'library' => 'image',
					        'fallback' => true
					    ),

					    'background_overlay' => array(
							'type' => 'color',
							'label' => __( 'Background overlay color', 'lrw-so-widgets-bundle' ),
							'default' => '#000',
						),

						'background_opacity' => array(
							'label' => __( 'Background opacity', 'lrw-so-widgets-bundle' ),
							'type' => 'slider',
							'min' => 0,
							'max' => 100,
							'default' => 30,
						),

						'height' => array(
							'type' => 'number',
							'label' => __( 'Height (optional)', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a fixed size for the box. More used if there is an image as background.', 'lrw-so-widgets-bundle' ),
						),

						'padding_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Padding bottom (optional)', 'lrw-so-widgets-bundle' ),
							'description' => __( 'More used if there is an image as background.', 'lrw-so-widgets-bundle' ),
							'default' => '0em',
						),

						'padding_top' => array(
							'type' => 'measurement',
							'label' => __( 'Padding top (optional)', 'lrw-so-widgets-bundle' ),
							'description' => __( 'More used if there is an image as background.', 'lrw-so-widgets-bundle' ),
							'default' => '0em',
						),
					),
				),

			    'promote_title' => array(
					'type' => 'section',
					'label' => __( 'Title', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(

						'title' => array(
							'type' => 'text',
							'label' => __( 'Promote title.', 'lrw-so-widgets-bundle', 'lrw-so-widgets-bundle' ),
							'sanitize' => 'promote_title'
						),

						'title_color' => array(
							'type' => 'color',
							'label' => __( 'Title color', 'lrw-so-widgets-bundle' ),
						),

						'title_type' => array(
							'type' => 'select',
							'label' => __( 'Element tag HTML', 'lrw-so-widgets-bundle' ),
							'default' => 'h4',
							'options' => array(
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
							),
						),

						'fontsize' => array(
							'type' => 'number',
							'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a font size or keep the default.', 'lrw-so-widgets-bundle' ),
						),
					),
				),

				'promote_content' => array(
					'type' => 'section',
					'label' => __( 'Content', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(

						'content' => array(
					        'type' => 'tinymce',
					        'label' => __( 'Content', 'lrw-so-widgets-bundle' ),
					        'rows' => 3
					    ),

					    'content_color' => array(
							'type' => 'color',
							'label' => __( 'Content color', 'lrw-so-widgets-bundle' ),
						),
					)
				),

				'buttons' => array(
					'type' => 'repeater',
					'label' => __( 'Buttons (optional)', 'lrw-so-widgets-bundle' ),
					'item_name' => __( 'Button', 'lrw-so-widgets-bundle' ),
					'item_label' => array(
						'selector' => "[id*='buttons-button-text']",
						'update_event' => 'change',
						'value_method' => 'val'
					),
					'hide' => true,
					'fields' => array(
						'button' => array(
							'type' => 'widget',
							'class' => 'SiteOrigin_Widget_Button_Widget',
							'hide' => true,
							'label' => __( 'Button', 'lrw-so-widgets-bundle' ),
						),
					)
				),

				'align' => array(
					'type' => 'select',
					'label' => __( 'Alignment', 'lrw-so-widgets-bundle' ),
					'default' => 'center',
					'options' => array(
						'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
						'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
						'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
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

		$less_vars = array();

		if ( ! empty( $instance['promote_background'] ) ) {
			$promote_background = $instance['promote_background'];

			if ( ! empty( $promote_background['background_overlay'] ) ) {
				$less_vars['background_overlay'] = $promote_background['background_overlay'];
			}

			if ( ! empty( $promote_background['background_opacity'] ) ) {
				$less_vars['background_opacity'] = intval( $promote_background['background_opacity'] ) / 100;
			}

			if ( ! empty( $promote_background['height'] ) ) {
				$less_vars['height'] = $promote_background['height'] . 'px';
			}

			if ( ! empty( $promote_background['padding_bottom'] ) ) {
				$less_vars['padding_bottom'] = $promote_background['padding_bottom'];
			}

			if ( ! empty( $promote_background['padding_top'] ) ) {
				$less_vars['padding_top'] = $promote_background['padding_top'];
			}
		}

		if ( ! empty( $instance['promote_title'] ) ) {
			$promote_title = $instance['promote_title'];

			if ( ! empty( $promote_title['title_color'] ) ) {
				$less_vars['title_color'] = $promote_title['title_color'];
			}

			if ( ! empty( $promote_title['fontsize'] ) ) {
				$less_vars['fontsize'] = $promote_title['fontsize'] . 'px';
			}
		}

		if ( ! empty( $instance['promote_content'] ) ) {
			$promote_content = $instance['promote_content'];

			if ( ! empty( $promote_content['conten_color'] ) ) {
				$less_vars['conten_color'] = $promote_content['conten_color'];
			}
		}

		return $less_vars;
	}

	function get_template_variables( $instance, $args ) {
		return array(
			'background' 			=> $instance['promote_background']['background'],
			'background_fallback' 	=> ! empty( $instance['promote_background']['background_fallback'] ) ? $instance['promote_background']['background_fallback'] : false,
			'height' 				=> $instance['promote_background']['height'],
			'align' 				=> $instance['align'],
			'title_type' 			=> $instance['promote_title']['title_type'],
			'title' 				=> $instance['promote_title']['title'],
			'content' 				=> $instance['promote_content']['content'],
			'buttons' 				=> $instance['buttons'],
		);
	}
}

siteorigin_widget_register( 'lrw-promote-box', __FILE__, 'LRW_Widget_Promote_Box' );
