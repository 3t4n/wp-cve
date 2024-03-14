<?php
/**
 * Widget Name: LRW - Word Rotator
 * Description: Simple text rotator.
 * Author: LRW
 * Author URI: https://github.com/luizrw
 */
class LRW_Widget_Word_Rotator extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'lrw-word-rotator',
		  	__( 'LRW - Word Rotator', 'lrw-so-widgets-bundle' ),
		  	array(
				'description' => __( 'Simple text rotator.', 'lrw-so-widgets-bundle' ),
				'panels_title' => false,
			),
		  	array(),
		  	array(

		  		'general' => array(
					'type' => 'section',
					'label' => __( 'General', 'lrw-so-widgets-bundle' ),
					'fields' => array(

				  		'prefix' => array(
							'type' => 'text',
							'label' => __( 'Text before animation (optional)', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Fixed text before the animation', 'lrw-so-widgets-bundle' ),
						),

						'suffix' => array(
							'type' => 'text',
							'label' => __( 'Text after animation (optional)', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Fixed text after the animations', 'lrw-so-widgets-bundle' ),
						),

						'strings' => array(
							'type' => 'textarea',
							'label' => __( 'Texts for animation', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Enter the text, each on a new line', 'lrw-so-widgets-bundle' ),
					        'rows' => 3
						),
					)
				),

				'settings' => array(
					'type' => 'section',
					'label' => __( 'Settings', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(
						'trigger' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Trigger on Viewport', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Check this to trigger the counter on viewport or on pageload.', 'lrw-so-widgets-bundle' )
						),

						'animation' => array(
							'type' => 'select',
							'label' => __( 'Animation', 'lrw-so-widgets-bundle' ),
							'description' => __( 'See this <a href="http://daneden.github.io/animate.css/" target="_blank">site</a> to preview the animation.', 'lrw-so-widgets-bundle' ),
							'options' => array(
								'bounce' 	 		 => __( 'Bounce', 'lrw-so-widgets-bundle' ),
							    'flash' 	 		 => __( 'Flash', 'lrw-so-widgets-bundle' ),
							    'pulse'		 		 => __( 'Pulse', 'lrw-so-widgets-bundle' ),
							    'rubberBand' 		 => __( 'Rubber Band', 'lrw-so-widgets-bundle' ),
							    'shake' 	 		 => __( 'Shake', 'lrw-so-widgets-bundle' ),
							    'swing' 	 		 => __( 'Swing', 'lrw-so-widgets-bundle' ),
							    'tada' 		 		 => __( 'Tada', 'lrw-so-widgets-bundle' ),
							    'wobble' 	 		 => __( 'Wobble', 'lrw-so-widgets-bundle' ),
							    'jello'		 		 => __( 'Jello', 'lrw-so-widgets-bundle' ),

							    'bounceIn' 		 	 => __( 'Bounce', 'lrw-so-widgets-bundle' ),
							    'bounceInDown' 	 	 => __( 'Bounce In Down', 'lrw-so-widgets-bundle' ),
							    'bounceInLeft'	 	 => __( 'Bounce In Left', 'lrw-so-widgets-bundle' ),
							    'bounceInRight'  	 => __( 'Bounce In Right', 'lrw-so-widgets-bundle' ),
							    'bounceInUp' 	 	 => __( 'Bounce In Up', 'lrw-so-widgets-bundle' ),
							    'bounceOut' 	 	 => __( 'Bounce Out', 'lrw-so-widgets-bundle' ),
							    'bounceOutDown'  	 => __( 'Bounce Out Down', 'lrw-so-widgets-bundle' ),
							    'bounceOutLeft'	 	 => __( 'Bounce Out Left', 'lrw-so-widgets-bundle' ),
							    'bounceOutRight' 	 => __( 'Bounce Out Right', 'lrw-so-widgets-bundle' ),
							    'bounceOutUp' 	 	 => __( 'Bounce Out Up', 'lrw-so-widgets-bundle' ),

							    'fadeIn' 		  	 => __( 'Fade In', 'lrw-so-widgets-bundle' ),
							    'fadeInDown' 	  	 => __( 'Fade In Down', 'lrw-so-widgets-bundle' ),
							    'fadeInDownBig'   	 => __( 'Fade In Down Big', 'lrw-so-widgets-bundle' ),
							    'fadeInLeft' 	  	 => __( 'Fade In Left', 'lrw-so-widgets-bundle' ),
							    'fadeInLeftBig'   	 => __( 'Fade In Left Big', 'lrw-so-widgets-bundle' ),
							    'fadeInRight' 	  	 => __( 'Fade In Right', 'lrw-so-widgets-bundle' ),
							    'fadeInRightBig'  	 => __( 'Fade In Right Big', 'lrw-so-widgets-bundle' ),
							    'fadeInUp' 		  	 => __( 'Fade In Up', 'lrw-so-widgets-bundle' ),
							    'fadeInUpBig' 	  	 => __( 'Fade In Up Big', 'lrw-so-widgets-bundle' ),
							    'fadeOut' 		  	 => __( 'Fade Out', 'lrw-so-widgets-bundle' ),
							    'fadeOutDown' 	  	 => __( 'Fade Out Down', 'lrw-so-widgets-bundle' ),
							    'fadeOutDownBig'  	 => __( 'Fade Out Big', 'lrw-so-widgets-bundle' ),
							    'fadeOutLeft' 	  	 => __( 'Fade Out Left', 'lrw-so-widgets-bundle' ),
							    'fadeOutLeftBig'  	 => __( 'Fade Out Right', 'lrw-so-widgets-bundle' ),
							    'fadeOutRight'	  	 => __( 'Fade Out Right', 'lrw-so-widgets-bundle' ),
							    'fadeOutRightBig' 	 => __( 'Fade Out Right Big', 'lrw-so-widgets-bundle' ),
							    'fadeOutUp' 	  	 => __( 'Fade Out Up', 'lrw-so-widgets-bundle' ),
							    'fadeOutUpBig' 	  	 => __( 'Fade Out Up Big', 'lrw-so-widgets-bundle' ),

							    'flipInX' 		  	 => __( 'Flip Horizontal', 'lrw-so-widgets-bundle' ),
							    'flipInY'		  	 => __( 'Flip Vertical', 'lrw-so-widgets-bundle' ),
							    'flipOutX'		  	 => __( 'Flip Out Horizontal', 'lrw-so-widgets-bundle' ),
							    'flipOutY' 		  	 => __( 'Flip Out Vertical', 'lrw-so-widgets-bundle' ),

							    'lightSpeedIn' 	  	 => __( 'Light Speed In', 'lrw-so-widgets-bundle' ),
							    'lightSpeedOut'   	 => __( 'Light Speed Out', 'lrw-so-widgets-bundle' ),

							    'rotateIn'			 => __( 'Rotate In', 'lrw-so-widgets-bundle' ),
							    'rotateInDownLeft' 	 => __( 'Rotate In Down Left', 'lrw-so-widgets-bundle' ),
							    'rotateInDownRight'  => __( 'Rotate In Down Right', 'lrw-so-widgets-bundle' ),
							    'rotateInUpLeft' 	 => __( 'Rotate In Up Left', 'lrw-so-widgets-bundle' ),
							    'rotateInUpRight' 	 => __( 'Rotate In Up Right', 'lrw-so-widgets-bundle' ),
							    'rotateOut' 		 => __( 'Rotate Out', 'lrw-so-widgets-bundle' ),
							    'rotateOutDownLeft'  => __( 'Rotate Out Down Left', 'lrw-so-widgets-bundle' ),
							    'rotateOutDownRight' => __( 'Rotate Out Down Right', 'lrw-so-widgets-bundle' ),
							    'rotateOutUpLeft' 	 => __( 'Rotate Out Up Left', 'lrw-so-widgets-bundle' ),
							    'rotateOutUpRight' 	 => __( 'Rotate Out Up Right', 'lrw-so-widgets-bundle' ),

							    'hinge' 			 => __( 'Hinge', 'lrw-so-widgets-bundle' ),

							    'rollIn' 			 => __( 'Roll In', 'lrw-so-widgets-bundle' ),
							    'rollOut' 			 => __( 'Roll Out', 'lrw-so-widgets-bundle' ),

							    'zoomIn' 			 => __( 'Zoom In', 'lrw-so-widgets-bundle' ),
							    'zoomInDown' 		 => __( 'Zoom In Down', 'lrw-so-widgets-bundle' ),
							    'zoomInLeft' 		 => __( 'Zoom In Left', 'lrw-so-widgets-bundle' ),
							    'zoomInRight' 		 => __( 'Zoom In Right', 'lrw-so-widgets-bundle' ),
							    'zoomInUp' 			 => __( 'Zoom In Up', 'lrw-so-widgets-bundle' ),
							    'zoomOut' 			 => __( 'Zoom Out', 'lrw-so-widgets-bundle' ),
							    'zoomOutDown' 		 => __( 'Zoom Out Down', 'lrw-so-widgets-bundle' ),
							    'zoomOutLeft' 		 => __( 'Zoom Out Left', 'lrw-so-widgets-bundle' ),
							    'zoomOutRight' 		 => __( 'Zoom Out Right', 'lrw-so-widgets-bundle' ),
							    'zoomOutUp' 		 => __( 'Zoom Out Up', 'lrw-so-widgets-bundle' ),

							    'slideInLeft' 		 => __( 'Slide In Left', 'lrw-so-widgets-bundle' ),
							    'slideInDown' 		 => __( 'Slide In Down', 'lrw-so-widgets-bundle' ),
							    'slideInRight' 		 => __( 'Slide In Right', 'lrw-so-widgets-bundle' ),
							    'slideInUp' 		 => __( 'Slide In Up', 'lrw-so-widgets-bundle' ),
							    'slideOutDown' 		 => __( 'Slide Out Down', 'lrw-so-widgets-bundle' ),
							    'slideOutLeft' 		 => __( 'Slide Out Left', 'lrw-so-widgets-bundle' ),
							    'slideOutRight' 	 => __( 'Slide Out Right', 'lrw-so-widgets-bundle' ),
							    'slideOutUp' 		 => __( 'Slide Out Up', 'lrw-so-widgets-bundle' ),
							),
						),

						'speed' => array(
							'type' => 'number',
							'label' => __( 'Speed', 'lrw-so-widgets-bundle' ),
							'description' => __( 'The delay between the changing of each phrase in milliseconds.', 'lrw-so-widgets-bundle' ),
							'default' => 2000,
						),
					)
				),

				'design' => array(
					'type' => 'section',
					'label' => __( 'Design', 'lrw-so-widgets-bundle' ),
					'hide' => true,
					'fields' => array(
						'align' => array(
							'type' => 'select',
							'label' => __( 'Texts align', 'lrw-so-widgets-bundle' ),
							'default' => 'center',
							'options' => array(
								'center' => __( 'Center', 'lrw-so-widgets-bundle' ),
								'right' => __( 'Right', 'lrw-so-widgets-bundle' ),
								'left' => __( 'Left', 'lrw-so-widgets-bundle' ),
							),
						),

						'tag' => array(
							'type' => 'select',
							'label' => __( 'Element tag', 'lrw-so-widgets-bundle' ),
							'options' => array(
								'p' => __( 'p', 'lrw-so-widgets-bundle' ),
								'h1' => __( 'h1', 'lrw-so-widgets-bundle' ),
								'h2' => __( 'h2', 'lrw-so-widgets-bundle' ),
								'h3' => __( 'h3', 'lrw-so-widgets-bundle' ),
								'h4' => __( 'h4', 'lrw-so-widgets-bundle' ),
								'h5' => __( 'h5', 'lrw-so-widgets-bundle' ),
								'h6' => __( 'h6', 'lrw-so-widgets-bundle' ),
							),
						),

						'fontsize' => array(
							'type' => 'measurement',
							'label' => __( 'Font size', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Text size for all text sentences', 'lrw-so-widgets-bundle' ),
						),

						'lineheight' => array(
							'type' => 'number',
							'label' => __( 'Line height', 'lrw-so-widgets-bundle' ),
							'description' => __( 'Set a line height or keep the default.', 'lrw-so-widgets-bundle' ),
						),

						'margin_top' => array(
							'type' => 'measurement',
							'label' => __( 'Margin top', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'margin_bottom' => array(
							'type' => 'measurement',
							'label' => __( 'Margin bottom', 'lrw-so-widgets-bundle' ),
							'default' => '0px',
						),

						'text_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
						),

						'animation_color' => array(
							'type' => 'color',
							'label' => __( 'Text color', 'lrw-so-widgets-bundle' ),
						),

						'bold' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __( 'Bold text animation', 'lrw-so-widgets-bundle' ),
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

	function enqueue_frontend_scripts( $instance ) {
		if ( ! empty( $instance['settings']['trigger'] ) ) {
			wp_enqueue_script( 'waypoints', plugin_dir_url( LRW_BASE_FILE ) . 'inc/assets/js/waypoints.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION );
		}

		parent::enqueue_frontend_scripts( $instance );
	}

	function initialize() {
		$this->register_frontend_styles(
	        array(
	            array( 'animate', siteorigin_widget_get_plugin_dir_url( 'lrw-word-rotator' ) . 'assets/css/animate.css', array(), LRW_BUNDLE_VERSION )
	        )
	    );

		$this->register_frontend_scripts(
			array(
				array( 'morphext', siteorigin_widget_get_plugin_dir_url( 'lrw-word-rotator' ) . 'assets/js/morphext.min.js', array( 'jquery' ), LRW_BUNDLE_VERSION ),
				array( 'rotatorinit', siteorigin_widget_get_plugin_dir_url( 'lrw-word-rotator' ) . 'assets/js/jquery.rotatorinit.js', array( 'jquery' ), LRW_BUNDLE_VERSION )
			)
		);
	}

	function get_less_variables( $instance ) {
		if ( empty( $instance ) ) return array();

		return array(
			'text_color' 	  => $instance['design']['text_color'],
			'animation_color' => $instance['design']['animation_color'],
			'margin_top' 	  => $instance['design']['margin_top'],
			'margin_bottom'   => $instance['design']['margin_bottom']
		);
	}

	function get_template_variables( $instance, $args ) {
		return array(
			'strings'		=> $instance['general']['strings'],
			'prefix'		=> $instance['general']['prefix'],
			'suffix'		=> $instance['general']['suffix'],
			'trigger' 		=> $instance['settings']['trigger'],
			'animation'		=> $instance['settings']['animation'],
			'speed' 		=> $instance['settings']['speed'],
			'align' 		=> $instance['design']['align'],
			'tag' 			=> $instance['design']['tag'],
			'fontsize'		=> $instance['design']['fontsize'],
			'lineheight'	=> $instance['design']['lineheight'],
			'bold'			=> $instance['design']['bold']
		);
	}
}

siteorigin_widget_register( 'lrw-word-rotator', __FILE__, 'LRW_Widget_Word_Rotator' );
